<?php

namespace App\Mail;

use App\Models\StudentPayment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OTPNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;
    public $student;

    /**
     * Create a new message instance.
     */
    public function __construct(StudentPayment $payment, User $student)
    {
        $this->payment = $payment;
        $this->student = $student;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'OTP for Installment Signing - ' . $this->payment->installment->inst_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.otp-notification',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
