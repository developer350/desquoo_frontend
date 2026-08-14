<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifyAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $notify;

    /**
     * Create a new message instance.
     */
    public function __construct($notify)
    {
        $this->notify = $notify->load('product:id,name', 'productVariant:id', 'productVariant.attributeValues:id,value,attribute_id', 'productVariant.attributeValues.attribute:id,name');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'A New Stock Alert Request | '.config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.notify-admin',
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
