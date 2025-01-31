<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;
    public $billForm;
    public $billTo;
    public $invoiceTaxes;
    public $invoice = null;
    public $savedInDb = false;

    /**
     * Create a new message instance.
     */
    public function __construct($data, $billForm, $billTo, $invoiceTaxes, $invoice = null, $savedInDb = false)
    {
        $this->data = $data;
        $this->billForm = $billForm;
        $this->billTo = $billTo;
        $this->invoiceTaxes = $invoiceTaxes;
        $this->invoice = $invoice;
        $this->savedInDb = $savedInDb;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        if ($this->savedInDb) {
            return new Envelope(
                from: new Address($this->invoice->bill_from_email, $this->invoice->bill_from_name),
                subject: 'Your Invoice',
            );
        }
        return new Envelope(
            from: new Address($this->data['bill_from_email'], $this->data['bill_from_name']),
            subject: 'Your Invoice',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        if ($this->savedInDb) {
            return new Content(
                view: 'pages.finance.admin.preview-invoice-send',
                with: ['invoice' => $this->invoice]
            );
        }
        return new Content(
            view: 'pages.finance.admin.preview-invoice',
            with: ['data' => $this->data, 'billForm' => $this->billForm, 'billTo' => $this->billTo, 'invoiceTaxes' => $this->invoiceTaxes]
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
