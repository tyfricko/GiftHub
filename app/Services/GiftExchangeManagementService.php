<?php

namespace App\Services;

use App\Mail\GiftAssignmentNotificationMail;
use App\Mail\GiftExchangeInvitationMail;
use App\Models\GiftAssignment;
use App\Models\GiftExchangeEvent;
use App\Models\GiftExchangeInvitation;
use App\Models\GiftExchangeParticipant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class GiftExchangeManagementService
{
    /**
     * Create a new gift exchange event.
     */
    public function createEvent(array $data, int $userId): GiftExchangeEvent
    {
        $event = GiftExchangeEvent::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'end_date' => $data['end_date'],
            'budget_max' => $data['budget_max'] ?? null,
            'created_by' => $userId,
            'requires_shipping_address' => $data['requires_shipping_address'] ?? false,
        ]);

        // Add creator as participant
        GiftExchangeParticipant::create([
            'event_id' => $event->id,
            'user_id' => $userId,
            'status' => 'accepted',
            'joined_at' => now(),
        ]);

        return $event;
    }

    /**
     * Update an existing event.
     */
    public function updateEvent(GiftExchangeEvent $event, array $data): GiftExchangeEvent
    {
        $event->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'end_date' => $data['end_date'],
            'budget_max' => $data['budget_max'] ?? null,
            'requires_shipping_address' => $data['requires_shipping_address'] ?? $event->requires_shipping_address,
        ]);

        return $event->fresh();
    }

    /**
     * Delete an event.
     */
    public function deleteEvent(GiftExchangeEvent $event): bool
    {
        return $event->delete();
    }

    /**
     * Send invitations to participants.
     */
    public function sendInvitations(GiftExchangeEvent $event, array $emails): array
    {
        $invitations = [];

        foreach ($emails as $email) {
            $token = bin2hex(random_bytes(16));

            $invitation = GiftExchangeInvitation::create([
                'event_id' => $event->id,
                'email' => $email,
                'token' => $token,
                'status' => 'pending',
                'sent_at' => now(),
            ]);

            // Send invitation email
            Mail::to($email)->send(new GiftExchangeInvitationMail($invitation, $event));

            $invitations[] = $invitation;
        }

        return $invitations;
    }

    /**
     * Respond to an invitation.
     */
    public function respondToInvitation(GiftExchangeInvitation $invitation, string $response, ?int $userId): ?GiftExchangeParticipant
    {
        $invitation->status = $response;
        $invitation->responded_at = now();
        $invitation->save();

        if ($response === 'accepted' && $userId) {
            return GiftExchangeParticipant::firstOrCreate([
                'event_id' => $invitation->event_id,
                'user_id' => $userId,
            ], [
                'status' => 'accepted',
                'joined_at' => now(),
            ]);
        }

        return null;
    }

    /**
     * Assign gifts to participants.
     *
     * @throws \Exception
     */
    public function assignGifts(GiftExchangeEvent $event): array
    {
        // Prevent duplicate assignments
        if ($event->assignments()->exists()) {
            throw new \Exception('Assignments already exist for this event.');
        }

        $participants = $event->participants()->where('status', 'accepted')->with('user')->get();

        if ($participants->count() < 2) {
            throw new \Exception('At least 2 participants are required for assignment.');
        }

        // If the event requires shipping addresses, ensure all accepted participants have provided one.
        if ($event->requiresShippingAddress()) {
            $missing = $participants->filter(function ($p) {
                return ! $p->hasShippingAddress();
            });

            if ($missing->count() > 0) {
                throw new \Exception('Cannot create assignments: '.$missing->count().' participant(s) have not provided shipping addresses.');
            }
        }

        // Use transaction to ensure atomicity
        $assignments = [];

        DB::transaction(function () use ($event, $participants, &$assignments) {
            // Shuffle participants for random assignment
            $givers = $participants->shuffle()->values();
            $recipients = $givers->slice(1)->concat($givers->slice(0, 1))->values();

            foreach ($givers as $i => $giver) {
                $recipient = $recipients[$i];

                // Prevent self-assignment (defensive)
                if ($giver->id === $recipient->id) {
                    throw new \Exception('Assignment failed due to self-assignment.');
                }

                $assignment = GiftAssignment::create([
                    'event_id' => $event->id,
                    'giver_id' => $giver->id,
                    'recipient_id' => $recipient->id,
                    'assigned_at' => now(),
                ]);

                // Send assignment notification email to giver (best-effort)
                try {
                    Mail::to($giver->user->email)->send(
                        new GiftAssignmentNotificationMail($event, $giver, $recipient)
                    );
                } catch (\Exception $e) {
                    \Log::error('Failed to send assignment email', [
                        'event_id' => $event->id,
                        'giver_id' => $giver->id,
                        'error' => $e->getMessage(),
                    ]);
                }

                $assignments[] = $assignment;
            }

            // Mark event as completed if model has a status column later (non-breaking)
            if (\Illuminate\Support\Facades\Schema::hasColumn('gift_exchange_events', 'status')) {
                $event->update(['status' => 'completed', 'processed_at' => now()]);
            }
        }, 5);

        return $assignments;
    }

    /**
     * Update shipping address for a participant.
     */
    public function updateShippingAddress(GiftExchangeParticipant $participant, array $address): GiftExchangeParticipant
    {
        $participant->shipping_address = $address;
        $participant->shipping_address_completed_at = now();
        $participant->save();

        return $participant;
    }

    /**
     * Get event with all loaded relations.
     */
    public function loadEventRelations(GiftExchangeEvent $event): GiftExchangeEvent
    {
        return $event->load([
            'participants.user',
            'invitations',
            'assignments.giver.user',
            'assignments.recipient.user',
        ]);
    }
}
