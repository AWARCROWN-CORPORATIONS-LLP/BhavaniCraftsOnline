<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserLoginNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $loginTime;
    public $ipAddress;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $loginTime, $ipAddress)
    {
        $this->user = $user;
        $this->loginTime = $loginTime;
        $this->ipAddress = $ipAddress;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Security Alert: New Login at Bhavani Crafts',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.user.login_notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
