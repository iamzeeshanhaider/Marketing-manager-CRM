<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MarketingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;
    public $email;

    /**
     * Create a new message instance.
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Add header for mailgun tracking
     */
    public function withSwiftMessage($message)
    {
        $message->getHeaders()
            ->addTextHeader('X-Mailgun-Track', 'opens');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(

            subject: 'Guardians Training - Level up your accounting career with expert training and hands-on work experience',
            from: new Address(config('mail.from.address')),
            tags: ['email-marketing'],
            metadata: [
                'o:tracking' => 'true',
                'o:tracking-clicks' => 'htmlonly',
                'o:tracking-opens' => 'true',
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.marketing-email',
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
