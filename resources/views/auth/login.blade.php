@extends('admin.layouts.layout')

@section('title', 'Login - Nusantara Store')

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

        .login-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.12);
            width: 100%;
            max-width: 440px;
            padding: 45px 50px;
            transition: all 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.15);
        }

        .login-title {
            font-size: 34px;
            font-weight: 800;
            background: linear-gradient(90deg, #9E6B3E, #00796B);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 1px;
        }

        .login-subtitle {
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
            .login-card {
                padding: 35px 25px;
                max-width: 90%;
            }
        }
    </style>
@endpush

@section('body')
    <div class="login-card">
        <div class="text-center mb-4">
            <h1 class="login-title">Nusantara Store</h1>
            <p class="login-subtitle">Masuk menggunakan akun Anda</p>
        </div>

        <form action="{{ route('login.proses') }}" method="post">
            @csrf
            <div class="mb-3">
                <x-metronic-input name="email" type="text" caption="Email" placeholder="Masukkan Email"
                    :viewtype="2" />
            </div>
            <div class="mb-3">
                <x-metronic-input name="password" type="password" caption="Password" placeholder="Masukkan Password"
                    :viewtype="2" />
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <x-checkbox name="remember" caption="Ingat Saya" />
                <a href="{{ route('forgot_password') }}" class="text-decoration-none text-danger fw-semibold">
                    Lupa Password?
                </a>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">MASUK</button>

            <div class="login-links d-flex flex-column gap-3 text-center">
                <a href="{{ route('register', ['role' => 'Buyer']) }}" class="fw-semibold">
                    Belum Punya Akun? Daftar Disini!
                </a>
            </div>
            <div class="divider">atau</div>

            <a href="{{ route('oauth.google.redirect') }}" class="btn btn-google w-100 py-2 mb-4">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google Logo">
                Masuk dengan Google
            </a>

        </form>
    </div>
@endsection
