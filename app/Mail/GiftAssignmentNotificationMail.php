<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\GiftExchangeEvent;
use App\Models\GiftExchangeParticipant;
use App\Models\User;

class GiftAssignmentNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $giver;
    public $recipient;

    /**
     * Create a new message instance.
     */
    public function __construct(GiftExchangeEvent $event, GiftExchangeParticipant $giver, GiftExchangeParticipant $recipient)
    {
        $this->event = $event;
        $this->giver = $giver;
        $this->recipient = $recipient;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Gift Exchange Assignment!')
            ->markdown('emails.gift_exchange.assignment')
            ->with([
                'event' => $this->event,
                'giver' => $this->giver,
                'recipient' => $this->recipient,
            ]);
    }
}