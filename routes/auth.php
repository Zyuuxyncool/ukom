<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login_proses'])->name('login.proses');
Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::get('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register_proses'])->name('register.proses');

// OTP endpoints for email verification during registration
Route::post('/auth/send-otp', [App\Http\Controllers\AuthController::class, 'sendOtp'])->name('auth.send_otp');
Route::post('/auth/verify-otp', [App\Http\Controllers\AuthController::class, 'verifyOtp'])->name('auth.verify_otp');
// Public link from email to verify OTP via GET
Route::get('/auth/verify-otp', [App\Http\Controllers\AuthController::class, 'verifyOtp'])->name('auth.verify_otp_link');
// Open bridge without verifying, used to send OTP back to app without server-side verification
Route::get('/auth/otp-bridge', [App\Http\Controllers\AuthController::class, 'otpBridge'])->name('auth.otp_bridge');
// Fetch current OTP for an email (for bridge to forward to app)
Route::get('/auth/get-otp', [App\Http\Controllers\AuthController::class, 'getOtp'])->name('auth.get_otp');
// Google OAuth
Route::get('/auth/google/redirect', [App\Http\Controllers\AuthController::class, 'googleRedirect'])->name('oauth.google.redirect');
Route::get('/auth/google/callback', [App\Http\Controllers\AuthController::class, 'googleCallback'])->name('oauth.google.callback');
// Complete registration after OTP (uses cached draft and verified session)
Route::get('/auth/complete-registration', [App\Http\Controllers\AuthController::class, 'completeRegistrationAfterOtp'])->name('auth.complete_registration');
Route::post('/auth/complete-registration', [App\Http\Controllers\AuthController::class, 'completeRegistrationAfterOtp'])->name('auth.complete_registration.post');

Route::get('/forgot_password', [App\Http\Controllers\AuthController::class, 'forgot_password'])->name('forgot_password');
Route::post('/forgot_password', [App\Http\Controllers\AuthController::class, 'forgot_password_proses'])->name('forgot_password.proses');
Route::get('/reset_password/{token}', [App\Http\Controllers\AuthController::class, 'reset_password'])->name('reset_password');
Route::post('/reset_password/{token}', [App\Http\Controllers\AuthController::class, 'reset_password_proses'])->name('reset_password.proses');
