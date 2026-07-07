<?php

namespace App\Mail;

use App\Models\Installment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InstallmentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $installment;
    public $student;

    /**
     * Create a new message instance.
     */
    public function __construct(Installment $installment, User $student)
    {
        $this->installment = $installment;
        $this->student = $student;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Installment Notification - ' . $this->installment->inst_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.installment-notification',
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
