<?php

namespace App\Mail;

use App\Models\GymSetting;
use App\Models\PaymentSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MemberPaymentRejectedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public PaymentSubmission $submission)
    {
    }

    public function envelope(): Envelope
    {
        $gymName = GymSetting::get('gym_name', 'Kinetic Apex');

        return new Envelope(
            subject: "Payment could not be verified - {$gymName}",
        );
    }

    public function content(): Content
    {
        $gymSettings = GymSetting::pluck('value', 'key');

        return new Content(
            view: 'emails.member-payment-rejected',
            with: [
                'submission'  => $this->submission,
                'gymSettings' => $gymSettings,
            ],
        );
    }
}
