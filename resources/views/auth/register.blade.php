@extends('admin.layouts.layout')

@section('title', 'Register - Nusantara Store')

@section('body-class', 'auth-bg')

@push('styles')
    <style>
        body {
            background: linear-gradient(135deg, #F3E9DD 0%, #D6EADF 50%, #F0E1C0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }

        .register-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.12);
            width: 100%;
            max-width: 440px;
            padding: 45px 50px;
            transition: all 0.3s ease;
        }

        .register-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.15);
        }

        .register-title {
            font-size: 34px;
            font-weight: 800;
            background: linear-gradient(90deg, #9E6B3E, #00796B);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 1px;
        }

        .register-subtitle {
            font-size: 15px;
            color: #6b7280;
        }

        .form-control {
            border-radius: 12px;
            padding: 10px 15px;
            border: 1px solid #E0E0E0;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #00796B;
            box-shadow: 0 0 0 3px rgba(0, 121, 107, 0.15);
        }

        .btn-primary {
            background: linear-gradient(90deg, #00796B, #9E6B3E);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #00695C, #8E5F35);
            transform: scale(1.03);
            box-shadow: 0 6px 15px rgba(0, 121, 107, 0.3);
        }

        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 12px;
            font-weight: 600;
            color: #444;
            transition: all 0.3s ease;
        }

        .btn-google:hover {
            background: #f9fafb;
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .btn-google img {
            width: 20px;
            height: 20px;
            margin-right: 8px;
        }

        .divider {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 25px 0;
            color: #9ca3af;
            font-size: 14px;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            border-top: 1px solid #e5e7eb;
            margin: 0 10px;
        }

        .login-links a {
            text-decoration: none;
            color: #9E6B3E;
            transition: color 0.3s ease;
        }

        .login-links a:hover {
            color: #7B542C;
        }

        @media (max-width: 576px) {
            .register-card {
                padding: 35px 25px;
                max-width: 90%;
            }
        }

        /* Modern OTP Modal (works with or without Bootstrap JS) */
        .otp-modal {
            position: fixed;
            inset: 0;
            display: none;
            /* shown via .show class when bootstrap isn't available */
            align-items: center;
            justify-content: center;
            background: rgba(17, 24, 39, 0.55);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            z-index: 1055;
            padding: 20px;
        }

        .otp-modal.show {
            display: flex;
        }

        .otp-modal .modal-dialog {
            margin: auto;
            width: 100%;
            max-width: 420px;
        }

        .otp-modal .modal-content {
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 20px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.92), rgba(255, 255, 255, 0.88));
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            animation: otp-pop 220ms ease-out;
        }

        .otp-modal .modal-header {
            background: linear-gradient(90deg, rgba(0, 121, 107, 0.1), rgba(158, 107, 62, 0.1));
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        }

        .otp-modal .modal-title {
            font-weight: 700;
            background: linear-gradient(90deg, #00796B, #9E6B3E);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .otp-modal .btn-close {
            filter: saturate(0.6);
        }

        /* OTP inputs grid styling */
        #otpModal .otp-subtitle {
            color: #64748b;
            font-size: 13px;
        }

        #otpModal .otp-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 12px;
            margin: 18px 0 6px;
        }

        #otpModal .otp-digit {
            width: 100%;
            text-align: center;
            font-size: 26px;
            font-weight: 700;
            padding: 12px 0;
            border-radius: 14px;
            border: 1px solid #E2E8F0;
            background: #fff;
            outline: none;
            transition: box-shadow .2s ease, border-color .2s ease, transform .08s ease;
        }

        #otpModal .otp-digit:focus {
            border-color: #00796B;
            box-shadow: 0 0 0 4px rgba(0, 121, 107, 0.18);
        }

        #otpModal .otp-digit.invalid {
            animation: shake .28s linear;
            border-color: #ef4444;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-4px);
            }

            75% {
                transform: translateX(4px);
            }
        }

        /* Actions */
        #otpModal #btnVerifyOtp.btn {
            border-radius: 12px;
            padding: 10px 18px;
            background: linear-gradient(90deg, #00796B, #9E6B3E);
            border: none;
            font-weight: 600;
            width: 100%;
        }

        #otpModal #btnVerifyOtp.btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(0, 121, 107, 0.25);
        }

        #otpModal #btnResendOtp.btn-link {
            color: #9E6B3E;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            margin-top: 6px;
        }

        #otpModal #btnResendOtp.btn-link:disabled {
            opacity: .65;
        }

        @keyframes otp-pop {
            from {
                opacity: 0;
                transform: translateY(8px) scale(.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
    </style>
@endpush

@section('body')
    <div class="register-card">
        <div class="text-center mb-4">
            <h1 class="register-title">Nusantara Store</h1>
            <p class="register-subtitle">Buat akun untuk mulai berbelanja</p>
        </div>

        <form action="{{ route('register.proses', ['role' => 'Buyer']) }}" method="post">
            @csrf
            <div class="mb-3">
                <x-metronic-input name="name" type="text" caption="Nama Lengkap" placeholder="Masukkan Nama Lengkap"
                    :value="old('name')" :viewtype="2" />
            </div>

            <div class="mb-3">
                <x-metronic-input name="email" type="text" caption="Email" placeholder="Masukkan Email"
                    :viewtype="2" />
            </div>

            <div class="mb-3">
                <x-metronic-input name="password" type="password" caption="Password" placeholder="Masukkan Password"
                    :viewtype="2" />
            </div>

            <div class="mb-3">
                <x-metronic-input name="password_confirmation" type="password" caption="Konfirmasi Password"
                    placeholder="Ulangi Password" :viewtype="2" />
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">DAFTAR</button>

            <div class="login-links text-center">
                <a href="{{ route('login') }}" class="fw-semibold">Sudah Punya Akun? Masuk Disini</a>
            </div>

            <div class="divider">atau</div>

            <a href="{{ route('oauth.google.redirect') }}" class="btn btn-google w-100 py-2 mb-4">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google Logo">
                Daftar dengan Google
            </a>
        </form>
    </div>

    <!-- Modal Verifikasi OTP -->
    <div class="modal fade otp-modal" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="otpModalLabel">Verifikasi OTP Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <!-- simple inline icon -->
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect x="6" y="2" width="12" height="20" rx="3" stroke="#00796B"
                                stroke-width="1.5" />
                            <circle cx="12" cy="18" r="1" fill="#00796B" />
                            <rect x="8" y="5" width="8" height="9" rx="1.5" fill="#E6FFFB" stroke="#9E6B3E"
                                stroke-width="1.2" />
                            <path d="M9 9h6M9 11h6" stroke="#9E6B3E" stroke-width="1.2" stroke-linecap="round" />
                        </svg>
                    </div>
                    <h5 class="text-center fw-bold mb-1">Account Verification</h5>
                    <p class="text-center otp-subtitle mb-2">Enter verify code below</p>
                    <p class="text-center mb-2">Kode dikirim ke: <strong id="otpTargetEmail">-</strong></p>

                    <div class="otp-grid" id="otpGrid">
                        <input class="otp-digit" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" />
                        <input class="otp-digit" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" />
                        <input class="otp-digit" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" />
                        <input class="otp-digit" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" />
                        <input class="otp-digit" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" />
                        <input class="otp-digit" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1" />
                    </div>
                    <div id="otpError" class="text-danger small mt-1 text-center" style="display:none;"></div>

                    <div class="mt-3">
                        <button id="btnVerifyOtp" class="btn btn-primary">Verify Code</button>
                    </div>
                    <div class="text-center">
                        <button id="btnResendOtp" class="btn btn-link">Resend Code</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Safely init metronic if available
        try {
            if (typeof init_form_element === 'function') {
                init_form_element();
            }
        } catch (e) {}

        // OTP flow: intercept submit, send OTP, show modal, verify, then submit
        (function() {
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', setupOtpFlow);
            } else {
                setupOtpFlow();
            }

            function setupOtpFlow() {
                const form = document.querySelector(
                    'form[action="{{ route('register.proses', ['role' => 'Buyer']) }}"]') || document.querySelector(
                    'form');
                const btnSubmit = form.querySelector('button[type="submit"]');
                const token = form.querySelector('input[name="_token"]').value;
                const sendOtpUrl = "{{ route('auth.send_otp') }}";
                const verifyOtpUrl = "{{ route('auth.verify_otp') }}";

                let otpVerified = false;
                let bootstrapModal = null;

                const otpModalEl = document.getElementById('otpModal');
                const otpTargetEmail = document.getElementById('otpTargetEmail');
                const otpInputs = Array.from(document.querySelectorAll('#otpModal .otp-digit'));
                const otpError = document.getElementById('otpError');
                const btnVerifyOtp = document.getElementById('btnVerifyOtp');
                const btnResendOtp = document.getElementById('btnResendOtp');
                const btnCloseModal = otpModalEl.querySelector('.btn-close');
                const emailInput = document.querySelector('input[name="email"]');
                const nameInput = document.querySelector('input[name="name"]');
                const passInput = document.querySelector('input[name="password"]');
                const passConfInput = document.querySelector('input[name="password_confirmation"]');
                // Nonaktifkan prefill email dari session untuk menghindari auto-isi tak diinginkan

                function showError(msg) {
                    otpError.textContent = msg || '';
                    otpError.style.display = msg ? 'block' : 'none';
                }

                function setSubmitting(state) {
                    btnSubmit.disabled = state;
                    if (state) {
                        btnSubmit.dataset.originalText = btnSubmit.textContent;
                        btnSubmit.textContent = 'Memproses...';
                    } else if (btnSubmit.dataset.originalText) {
                        btnSubmit.textContent = btnSubmit.dataset.originalText;
                    }
                }

                // Persist latest draft locally so bridge can read it if diperlukan
                function saveDraftLocal() {
                    try {
                        const payload = {
                            email: (emailInput?.value || '').trim(),
                            name: (nameInput?.value || '').trim(),
                            password: passInput?.value || '',
                            password_confirmation: passConfInput?.value || '',
                            role: 'Buyer',
                            ts: Date.now()
                        };
                        localStorage.setItem('ukom_reg_draft', JSON.stringify(payload));
                    } catch (_) {}
                }

                async function sendOtp(email) {
                    saveDraftLocal();
                    const res = await fetch(sendOtpUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            email,
                            draft: {
                                name: (nameInput?.value || '').trim(),
                                password: passInput?.value || '',
                                password_confirmation: passConfInput?.value || '',
                                role: 'Buyer'
                            }
                        })
                    });
                    if (!res.ok) {
                        const data = await res.json().catch(() => ({
                            message: 'Gagal mengirim OTP.'
                        }));
                        throw new Error(data.message || 'Gagal mengirim OTP.');
                    }
                    return res.json();
                }

                async function verifyOtp(email, otp) {
                    const res = await fetch(verifyOtpUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            email,
                            otp
                        })
                    });
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok || data.success !== true) {
                        throw new Error(data.message || 'Kode OTP salah.');
                    }
                    return data;
                }

                function showModalFor(email) {
                    otpTargetEmail.textContent = email;
                    showError('');
                    otpInputs.forEach(i => i.value = '');
                    // focus first box
                    setTimeout(() => {
                        otpInputs[0]?.focus();
                    }, 50);
                    if (window.bootstrap && window.bootstrap.Modal) {
                        bootstrapModal = bootstrapModal || new bootstrap.Modal(otpModalEl);
                        bootstrapModal.show();
                    } else {
                        // Fallback if Bootstrap JS not present: use custom overlay
                        otpModalEl.classList.add('show');
                        document.body.style.overflow = 'hidden';
                    }
                }

                function hideModal() {
                    if (bootstrapModal) bootstrapModal.hide();
                    otpModalEl.classList.remove('show');
                    document.body.style.overflow = '';
                    // restore submit button state
                    setSubmitting(false);
                }

                // Listen for OTP verification from email link (BroadcastChannel/localStorage)
                function handleExternalVerified(evtEmail) {
                    const currentEmail = (emailInput?.value || '').trim();
                    if (!currentEmail || currentEmail.toLowerCase() !== String(evtEmail || '').toLowerCase()) return;
                    otpVerified = true;
                    hideModal();
                    // Auto-submit hanya jika data form utama sudah lengkap
                    if (isFormReady()) {
                        setSubmitting(true);
                        form.submit();
                    } else {
                        setSubmitting(false);
                        showVerifiedNote();
                    }
                }
                // Auto-fill OTP digits coming from email link and verify seamlessly
                function handleOtpDigitsFromEmail(payload) {
                    if (!payload) return;
                    const evtEmail = String(payload.email || '').trim();
                    const currentEmail = (emailInput?.value || '').trim();
                    const otp = String(payload.otp || '').replace(/\D/g, '').slice(0, otpInputs.length);
                    if (!evtEmail || !currentEmail || currentEmail.toLowerCase() !== evtEmail.toLowerCase()) return;
                    if (otp.length !== otpInputs.length) return; // need full 6 digits

                    // Clear one-time payload if present to avoid reusing on future visits
                    try {
                        localStorage.removeItem('ukom_otp_payload');
                    } catch (_) {}

                    // Ensure modal is visible for user context, then fill digits
                    if (!otpModalEl.classList.contains('show')) {
                        showModalFor(currentEmail);
                    }
                    // Fill the boxes
                    otpInputs.forEach((i, idx) => i.value = otp[idx] || '');

                    // Verify automatically
                    (async () => {
                        showError('');
                        btnVerifyOtp.disabled = true;
                        const prevText = btnVerifyOtp.textContent;
                        btnVerifyOtp.textContent = 'Memverifikasi...';
                        try {
                            await verifyOtp(currentEmail, otp);
                            otpVerified = true;
                            hideModal();
                            // Submit if form ready, otherwise show verified note
                            if (isFormReady()) {
                                setSubmitting(true);
                                form.submit();
                            } else {
                                showVerifiedNote();
                            }
                        } catch (err) {
                            showError(err?.message || 'Kode OTP tidak valid.');
                        } finally {
                            btnVerifyOtp.disabled = false;
                            btnVerifyOtp.textContent = prevText || 'Verify Code';
                        }
                    })();
                }
                try {
                    if ('BroadcastChannel' in window) {
                        const ch = new BroadcastChannel('ukom_otp_channel');
                        ch.onmessage = (ev) => {
                            if (!ev || !ev.data) return;
                            if (ev.data.type === 'otp_verified') {
                                handleExternalVerified(ev.data.email);
                            } else if (ev.data.type === 'otp_from_email') {
                                handleOtpDigitsFromEmail(ev.data);
                            }
                        };
                    }
                } catch (e) {}
                window.addEventListener('storage', function(e) {
                    if (e.key === 'ukom_otp_verified' && e.newValue) {
                        try {
                            const payload = JSON.parse(e.newValue);
                            handleExternalVerified(payload.email);
                        } catch (_) {}
                    }
                    if (e.key === 'ukom_otp_payload' && e.newValue) {
                        try {
                            const payload = JSON.parse(e.newValue);
                            handleOtpDigitsFromEmail(payload);
                        } catch (_) {}
                    }
                });

                // In case payload was written before this script loaded, try consuming it now
                try {
                    const rawPayload = localStorage.getItem('ukom_otp_payload');
                    if (rawPayload) {
                        const payload = JSON.parse(rawPayload);
                        handleOtpDigitsFromEmail(payload);
                    }
                } catch (_) {}

                // Same-tab reload fallback: check session + localStorage at load
                (function tryAutoContinueFromSession() {
                    // Dinonaktifkan: jangan auto-mengisi email dari localStorage/session.
                    // Tetap biarkan user memasukkan email secara manual sebelum verifikasi OTP.
                })();

                function isFormReady() {
                    const nameOk = !!(nameInput && nameInput.value.trim());
                    const passOk = !!(passInput && passInput.value);
                    const confOk = !!(passConfInput && passConfInput.value);
                    const match = passOk && confOk && passInput.value === passConfInput.value;
                    return nameOk && match;
                }

                function showVerifiedNote() {
                    try {
                        const parent = emailInput?.closest('.mb-3') || form;
                        let note = document.getElementById('otpVerifiedNote');
                        if (!note) {
                            note = document.createElement('div');
                            note.id = 'otpVerifiedNote';
                            note.className = 'small mt-2';
                            note.style.color = '#0f766e';
                            parent.appendChild(note);
                        }
                        note.textContent = 'Email telah diverifikasi. Silakan lengkapi data kemudian klik DAFTAR.';
                    } catch (_) {}
                }

                form.addEventListener('submit', async function(e) {
                    if (otpVerified) return; // allow actual submit
                    e.preventDefault();

                    const email = form.querySelector('input[name="email"]').value?.trim();
                    if (!email) {
                        // Let backend validation handle; but we block OTP flow until email present
                        return form.submit();
                    }

                    try {
                        setSubmitting(true);
                        await sendOtp(email);
                        showModalFor(email);
                    } catch (err) {
                        setSubmitting(false);
                        alert(err.message || 'Terjadi kesalahan saat mengirim OTP.');
                    }
                });

                function getOtpValue() {
                    return otpInputs.map(i => (i.value || '').replace(/\D/g, '')).join('');
                }

                btnVerifyOtp.addEventListener('click', async function() {
                    const email = otpTargetEmail.textContent;
                    const otp = getOtpValue();
                    if (otp.length !== 6) {
                        showError('Masukkan 6 digit kode OTP.');
                        // mark invalid ones
                        otpInputs.forEach(i => {
                            if (!i.value) {
                                i.classList.add('invalid');
                                setTimeout(() => i.classList.remove('invalid'), 300);
                            }
                        });
                        otpInputs.find(i => !i.value)?.focus();
                        return;
                    }
                    showError('');
                    btnVerifyOtp.disabled = true;
                    btnVerifyOtp.textContent = 'Memverifikasi...';
                    try {
                        await verifyOtp(email, otp);
                        otpVerified = true;
                        hideModal();
                        form.submit();
                    } catch (err) {
                        showError(err.message || 'Kode OTP salah.');
                    } finally {
                        btnVerifyOtp.disabled = false;
                        btnVerifyOtp.textContent = 'Verifikasi';
                    }
                });

                btnResendOtp.addEventListener('click', async function(ev) {
                    ev.preventDefault();
                    const email = otpTargetEmail.textContent;
                    btnResendOtp.disabled = true;
                    btnResendOtp.textContent = 'Mengirim...';
                    try {
                        await sendOtp(email);
                        // optional: show a small info
                        showError('Kode OTP telah dikirim ulang.');
                    } catch (err) {
                        showError(err.message || 'Gagal mengirim ulang OTP.');
                    } finally {
                        // simple cooldown
                        let s = 30;
                        const timer = setInterval(() => {
                            s--;
                            btnResendOtp.textContent = s > 0 ? `Kirim Ulang (${s}s)` :
                                'Kirim Ulang';
                            if (s <= 0) {
                                clearInterval(timer);
                                btnResendOtp.disabled = false;
                            }
                        }, 1000);
                    }
                });

                // Close modal handlers to restore submit button state
                if (btnCloseModal) {
                    btnCloseModal.addEventListener('click', () => {
                        hideModal();
                    });
                }
                // Clicking overlay (fallback) closes too
                otpModalEl.addEventListener('click', (e) => {
                    if (e.target === otpModalEl) hideModal();
                });
                // Bootstrap hidden event
                if (window.bootstrap) {
                    otpModalEl.addEventListener('hidden.bs.modal', () => setSubmitting(false));
                }
                // Keep draft updated as user types
                [nameInput, passInput, passConfInput, emailInput].forEach(el => {
                    if (!el) return;
                    el.addEventListener('input', () => {
                        try {
                            const payload = {
                                email: (emailInput?.value || '').trim(),
                                name: (nameInput?.value || '').trim(),
                                password: passInput?.value || '',
                                password_confirmation: passConfInput?.value || '',
                                role: 'Buyer',
                                ts: Date.now()
                            };
                            localStorage.setItem('ukom_reg_draft', JSON.stringify(payload));
                        } catch (_) {}
                    });
                });
                // OTP input behaviors: auto-advance, backspace, digits only, paste support
                otpInputs.forEach((input, idx) => {
                    input.addEventListener('input', e => {
                        const val = input.value.replace(/\D/g, '');
                        input.value = val.slice(-1); // keep last digit only
                        if (input.value && idx < otpInputs.length - 1) {
                            otpInputs[idx + 1].focus();
                        }
                    });
                    input.addEventListener('keydown', e => {
                        if (e.key === 'Backspace' && !input.value && idx > 0) {
                            otpInputs[idx - 1].focus();
                            otpInputs[idx - 1].value = '';
                        }
                        // Allow only control keys and digits
                        const allowed = ['Backspace', 'Tab', 'ArrowLeft', 'ArrowRight', 'Delete'];
                        if (!allowed.includes(e.key) && !/^[0-9]$/.test(e.key)) {
                            e.preventDefault();
                        }
                    });
                    input.addEventListener('paste', e => {
                        const data = (e.clipboardData || window.clipboardData).getData('text');
                        const digits = (data || '').replace(/\D/g, '').slice(0, otpInputs.length);
                        if (digits.length) {
                            e.preventDefault();
                            otpInputs.forEach((i, iIdx) => i.value = digits[iIdx] || '');
                            const next = digits.length >= otpInputs.length ? otpInputs[otpInputs
                                .length - 1] : otpInputs[digits.length];
                            next?.focus();
                        }
                    });
                });

            }
        })();
    </script>
@endpush
