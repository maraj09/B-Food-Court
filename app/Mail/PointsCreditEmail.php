<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PointsCreditEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $user;
    protected $points;
    protected $settings;
    protected $messageForPoints;
    public $content;
    public $subject;

    /**
     * Create a new message instance.
     */
    public function __construct($userId, $points, $message)
    {
        $this->points = $points;
        $this->messageForPoints = $message;
        $this->settings = Setting::first();
        $this->user = User::where('id', $userId)->first();

        $template = EmailTemplate::where('trigger', 'points_credit')->first();

        $this->content = str_replace(
            ['{user_name}', '{user_email}', '{user_phone}', '{user_id}', '{user_points}', '{user_birthday}', '{user_points_added}', '{user_points_message}', '{user_coupon_discount}', '{user_coupon_code}'],
            [$this->user->name,  $this->user->email,  $this->user->phone, $this->user->id, $this->user->point->points, $this->user->customer->date_of_birth, $points, $message, 0, ''],
            $template->body
        );

        $this->subject = $template->subject;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.custom-emails.index',
            with: ['emailBody' => $this->content]
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
