<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi OTP</title>
    <style>
        /* Email-safe base */
        body {
            margin: 0;
            padding: 0;
            background: #f6f8fc;
            color: #0f172a;
        }

        table {
            border-collapse: collapse;
        }

        a {
            color: #0e7490;
            text-decoration: none;
        }

        /* Layout */
        .wrap {
            width: 100%;
            padding: 28px 12px;
            background: #f6f8fc;
        }

        .card {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid #eef2f7;
            box-shadow: 0 14px 40px rgba(2, 6, 23, .06);
        }

        /* Aesthetic divider instead of solid color ribbon */
        .divider {
            height: 1px;
            background: linear-gradient(90deg, rgba(0, 0, 0, 0), #e5e7eb, rgba(0, 0, 0, 0));
        }

        .head {
            padding: 18px 24px 6px;
        }

        /* Brand plain text (no gradient background) */
        .brand {
            margin: 0;
            font-weight: 900;
            font-size: 18px;
            letter-spacing: .3px;
            color: #0f172a;
        }

        .content {
            padding: 8px 24px 22px;
        }

        .title {
            margin: 8px 0 6px;
            font-size: 24px;
            font-weight: 900;
        }

        .subtitle {
            margin: 0 0 18px;
            font-size: 13px;
            color: #64748b;
        }

        /* OTP */
        .otp-row {
            text-align: center;
            padding: 8px 0 16px;
        }

        .otp {
            display: inline-block;
            min-width: 52px;
            padding: 12px 10px;
            margin: 0 6px;
            font-size: 22px;
            font-weight: 800;
            background: #fff;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            box-shadow: 0 2px 0 rgba(2, 6, 23, .04) inset;
        }

        /* Button */
        .btn-row {
            text-align: center;
        }

        .btn {
            display: inline-block;
            text-align: center;
            padding: 12px 18px;
            border-radius: 14px;
            font-weight: 800;
            color: #ffffff;
            background: linear-gradient(90deg, #00796B, #9E6B3E);
            box-shadow: 0 8px 18px rgba(0, 121, 107, .22);
        }

        .note {
            font-size: 12px;
            color: #6b7280;
            text-align: center;
            margin: 12px 0 0;
        }

        .footer {
            padding: 16px 20px 24px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
        }

        @media (max-width:480px) {
            .otp {
                min-width: 42px;
                margin: 0 4px;
            }

            .title {
                font-size: 21px;
            }
        }
    </style>
</head>

<body>
    <!-- Preheader (hidden preview) -->
    <div style="display:none;visibility:hidden;opacity:0;height:0;overflow:hidden;mso-hide:all">
        Kode OTP Anda: {{ $otp }} • Berlaku {{ $expiresMinutes ?? 5 }} menit
    </div>

    <table role="presentation" class="wrap" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <table role="presentation" class="card" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="head">
                            <p class="brand">Nusantara Store</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="divider"></td>
                    </tr>
                    <tr>
                        <td class="content">
                            <h1 class="title">Verifikasi Akun</h1>
                            <p class="subtitle">Gunakan 6 digit kode verifikasi berikut untuk melanjutkan proses
                                pendaftaran Anda.</p>

                            <div class="otp-row">
                                @php $digits = str_split($otp ?? ''); @endphp
                                @foreach ($digits as $d)
                                    <span class="otp">{{ $d }}</span>
                                @endforeach
                            </div>

                            <!-- Tombol copy dihapus; pengguna menyalin kode OTP secara manual dari kotak di atas. -->

                            <p class="note">Kode berlaku selama {{ $expiresMinutes ?? 5 }} menit. Dikirim ke
                                <strong>{{ $email }}</strong>.
                                Jika Anda tidak meminta kode ini, abaikan email ini.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td class="footer">&copy; {{ date('Y') }} {{ $appName ?? config('app.name') }}. Semua hak
                            dilindungi.</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
