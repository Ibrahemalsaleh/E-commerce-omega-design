<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * رمز إعادة تعيين كلمة المرور
     *
     * @var string
     */
    public $token;

    /**
     * إنشاء حالة جديدة من الرسالة
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * الحصول على مغلف الرسالة
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'إعادة تعيين كلمة المرور',
        );
    }

    /**
     * الحصول على محتوى الرسالة
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reset-password',
        );
    }

    /**
     * الحصول على المرفقات للرسالة
     *
     * @return array
     */
    public function attachments(): array
    {
        return [];
    }
}