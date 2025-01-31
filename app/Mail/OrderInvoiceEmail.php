<?php

namespace App\Mail;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderInvoiceEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    protected $user;
    protected $settings;
    protected $order;
    protected $payment;
    /**
     * Create a new message instance.
     */
    public function __construct($userId, $order, $payment)
    {
        $this->settings = Setting::first();
        $this->order = $order;
        $this->payment = $payment;
        $this->user = User::where('id', $userId)->first();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Thanks for your order!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.orderInvoice',
            with: ['user' => $this->user, 'order' => $this->order, 'settings' => $this->settings, 'payment' => $this->payment]
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
