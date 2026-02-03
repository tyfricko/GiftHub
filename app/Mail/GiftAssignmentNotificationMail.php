<?php

namespace App\Mail;

use App\Models\GiftExchangeEvent;
use App\Models\GiftExchangeParticipant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GiftAssignmentNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;

    public $giver;

    public $recipient;

    // Additional data passed to the view
    public $suggestedItem;

    public $recipientProfileUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(GiftExchangeEvent $event, GiftExchangeParticipant $giver, GiftExchangeParticipant $recipient)
    {
        $this->event = $event;
        $this->giver = $giver;
        $this->recipient = $recipient;

        // Prepare suggested item and recipient profile URL
        $this->suggestedItem = null;
        $this->recipientProfileUrl = null;

        try {
            $user = $recipient->user()->first();

            if ($user) {
                // Build base query for the user's wishlist items
                $itemsQuery = $user->wishlistItems();

                // If event has a budget_max, prefer items within that budget
                if (! empty($event->budget_max)) {
                    $budgetItems = (clone $itemsQuery)
                        ->whereNotNull('price')
                        ->where('price', '<=', $event->budget_max)
                        ->inRandomOrder()
                        ->first();

                    if ($budgetItems) {
                        $item = $budgetItems;
                    } else {
                        // fallback to any item
                        $item = $itemsQuery->inRandomOrder()->first();
                    }
                } else {
                    $item = $itemsQuery->inRandomOrder()->first();
                }

                if (isset($item) && $item) {
                    $this->suggestedItem = [
                        'title' => $item->itemname ?? $item->title ?? 'Suggested gift',
                        'url' => $item->url ?? null,
                        'price' => $item->price ?? null,
                        'currency' => $item->currency ?? null,
                        'image_url' => $item->image_url ?? null,
                    ];
                }

                // Recipient profile link (absolute)
                if (method_exists($user, 'username')) {
                    // route helper will generate absolute URL when used with third param true
                    $this->recipientProfileUrl = route('profile.show', $user->username);
                } else {
                    $this->recipientProfileUrl = route('profile.show', $user->username ?? $user->id);
                }
            }
        } catch (\Throwable $e) {
            // Don't break email sending — log and continue without suggestions
            \Log::error('Failed to prepare gift suggestion for assignment email', [
                'event_id' => $event->id ?? null,
                'giver_id' => $giver->id ?? null,
                'recipient_id' => $recipient->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
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
                'suggestedItem' => $this->suggestedItem,
                'recipientProfileUrl' => $this->recipientProfileUrl,
            ]);
    }
}
