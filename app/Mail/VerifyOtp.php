<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyOtp extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;
    public string $email;
    public string $appName;
    public int $expiresMinutes;

    public function __construct(string $otp, string $email, int $expiresMinutes = 5)
    {
        $this->otp = $otp;
        $this->email = $email;
        $this->appName = config('app.name', 'Aplikasi');
        $this->expiresMinutes = $expiresMinutes;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode OTP Verifikasi - ' . $this->appName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.verify_otp',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
