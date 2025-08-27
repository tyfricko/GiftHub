<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Mail\GiftExchangeInvitationMail;
use App\Mail\GiftAssignmentNotificationMail;
use App\Models\GiftExchangeEvent;
use App\Models\GiftExchangeInvitation;
use App\Models\GiftExchangeParticipant;
use App\Models\GiftAssignment;

class GiftExchangeController extends Controller
{
    // 1. Create a new gift exchange event
    public function createEvent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'end_date' => 'required|date|after:now',
            'budget_max' => 'nullable|numeric|min:0',
        ]);

        $user = $request->user();

        $event = \App\Models\GiftExchangeEvent::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'end_date' => $validated['end_date'],
            'budget_max' => $validated['budget_max'] ?? null,
            'created_by' => $user->id,
        ]);

        $participant = \App\Models\GiftExchangeParticipant::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => 'accepted',
            'joined_at' => now(),
        ]);

        return response()->json([
            'event' => $event,
            'participant' => $participant,
        ], 201);
    }

    // 2. Invite participants by email
    public function inviteParticipants(Request $request, $eventId)
    {
        $validated = $request->validate([
            'emails' => 'required|array|min:1',
            'emails.*' => 'required|email',
        ]);

        $event = \App\Models\GiftExchangeEvent::findOrFail($eventId);

        $invitations = [];
        foreach ($validated['emails'] as $email) {
            // Generate a unique token
            $token = bin2hex(random_bytes(16));

            $invitation = \App\Models\GiftExchangeInvitation::create([
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

        return response()->json([
            'invitations' => $invitations,
        ], 201);
    }

    // 3. Respond to invitation (accept/decline)
    public function respondToInvitation(Request $request, $token)
    {
        $validated = $request->validate([
            'response' => 'required|in:accepted,declined',
        ]);

        $invitation = \App\Models\GiftExchangeInvitation::where('token', $token)->firstOrFail();

        // Update invitation status
        $invitation->status = $validated['response'];
        $invitation->responded_at = now();
        $invitation->save();

        $participant = null;
        if ($validated['response'] === 'accepted') {
            $user = $request->user();
            if ($user) {
                // Add as participant if not already
                $participant = \App\Models\GiftExchangeParticipant::firstOrCreate([
                    'event_id' => $invitation->event_id,
                    'user_id' => $user->id,
                ], [
                    'status' => 'accepted',
                    'joined_at' => now(),
                ]);
            } else {
                // TODO: Handle new user registration flow
                // After registration, add as participant
            }
        }

        return response()->json([
            'invitation' => $invitation,
            'participant' => $participant,
        ]);
    }

    // 4. Get participants for an event
    public function getParticipants($eventId)
    {
        $event = \App\Models\GiftExchangeEvent::findOrFail($eventId);
        $participants = $event->participants()->with('user')->get();

        return response()->json([
            'participants' => $participants,
        ]);
    }

    // 5. Assign gifts (Secret Santa style)
    public function assignGifts(Request $request, $eventId)
    {
        $event = \App\Models\GiftExchangeEvent::findOrFail($eventId);

        // Authorization: only event owner may trigger assignments
        $user = $request->user();
        if (!$user || $user->id !== $event->created_by) {
            return response()->json(['error' => 'Unauthorized. Only the event owner can assign gifts.'], 403);
        }

        // Prevent duplicate assignments
        if ($event->assignments()->exists()) {
            return response()->json(['error' => 'Assignments already exist for this event.'], 400);
        }

        $participants = $event->participants()->where('status', 'accepted')->with('user')->get();

        if ($participants->count() < 2) {
            return response()->json(['error' => 'At least 2 participants are required for assignment.'], 400);
        }

        // If the event requires shipping addresses, ensure all accepted participants have provided one.
        if ($event->requiresShippingAddress()) {
            $missing = $participants->filter(function ($p) {
                return !$p->hasShippingAddress();
            });

            if ($missing->count() > 0) {
                $message = 'Cannot create assignments: ' . $missing->count() . ' participant(s) have not provided shipping addresses.';
                // If request expects JSON, return JSON error, otherwise redirect back to event page with error.
                if ($request->wantsJson() || $request->expectsJson()) {
                    return response()->json(['error' => $message, 'missing_count' => $missing->count()], 400);
                }
                return redirect()->route('gift-exchange.show', $event->id)
                                 ->with('error', $message);
            }
        }

        // Use transaction to ensure atomicity
        $assignments = [];
        try {
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
                            'error' => $e->getMessage()
                        ]);
                    }

                    $assignments[] = $assignment;
                }

                // Mark event as completed if model has a status column later (non-breaking)
                if (Schema::hasColumn('gift_exchange_events', 'status')) {
                    $event->update(['status' => 'completed', 'processed_at' => now()]);
                }
            }, 5);
        } catch (\Exception $e) {
            \Log::error('Gift assignment transaction failed', [
                'event_id' => $event->id,
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Assignment failed: ' . $e->getMessage()], 500);
        }

        return redirect()->route('gift-exchange.show', $event->id)
                         ->with('success', 'Gift assignments created successfully!');
    }

// 6. Get assignments for an event
public function getAssignments($eventId)
{
    $event = \App\Models\GiftExchangeEvent::findOrFail($eventId);
    $assignments = $event->assignments()
        ->with(['giver.user', 'recipient.user'])
        ->get();

    return response()->json([
        'assignments' => $assignments,
    ]);
}

    // Gift Exchange Dashboard (Web)
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $events = \App\Models\GiftExchangeEvent::where('created_by', $user->id)->orderBy('end_date', 'desc')->get();

        return view('gift-exchange', [
            'events' => $events,
        ]);
    }

    /**
     * Show a specific gift exchange event dashboard (Web).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GiftExchangeEvent  $event
     * @return \Illuminate\View\View
     */
    public function show(Request $request, GiftExchangeEvent $event)
    {
        // Eager load useful relations
        $event->load([
            'participants.user',
            'invitations',
            'assignments.giver.user',
            'assignments.recipient.user',
        ]);

        $participants = $event->participants()->with('user')->get();
        $invitations = $event->invitations()->get();

        // Log for debugging
        \Log::info('Event show data', [
            'event_id' => $event->id,
            'participants_count' => $participants->count(),
            'participants_data' => $participants->map(function($p) {
                return [
                    'id' => $p->id,
                    'user_id' => $p->user_id,
                    'status' => $p->status,
                    'user_email' => $p->user->email ?? 'N/A'
                ];
            }),
            'invitations_count' => $invitations->count(),
            'invitations_data' => $invitations->map(function($i) {
                return [
                    'id' => $i->id,
                    'email' => $i->email,
                    'status' => $i->status
                ];
            })
        ]);

        // If the assignments relation exists, eager load giver/recipient users
        $assignments = method_exists($event, 'assignments') ? $event->assignments()->with(['giver.user', 'recipient.user'])->get() : collect([]);

        return view('gift-exchange.show', [
            'event' => $event,
            'participants' => $participants,
            'invitations' => $invitations,
            'assignments' => $assignments,
        ]);
    }

    // Handle Event Creation (Web)
/**
     * Show edit form for an event (owner-only).
     */
    public function edit(Request $request, GiftExchangeEvent $event)
    {
        $user = $request->user();
        if (!$user || $user->id !== $event->created_by) {
            abort(403, 'You are not authorized to edit this event.');
        }

        return view('gift-exchange.edit', [
            'event' => $event,
        ]);
    }

    /**
     * Update an existing event (owner-only).
     */
    public function update(Request $request, GiftExchangeEvent $event)
    {
        $user = $request->user();
        if (!$user || $user->id !== $event->created_by) {
            abort(403, 'You are not authorized to update this event.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'end_date' => 'required|date|after:now',
            'budget_max' => 'nullable|numeric|min:0',
            'requires_shipping_address' => 'boolean', // Add this
        ]);

        $event->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'end_date' => $validated['end_date'],
            'budget_max' => $validated['budget_max'] ?? null,
            'requires_shipping_address' => $validated['requires_shipping_address'] ?? $event->requires_shipping_address, // Add this
        ]);

        return redirect()->route('gift-exchange.show', $event->id)->with('success', 'Event updated successfully!');
    }
    public function createEventWeb(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'end_date' => 'required|date|after:now',
            'budget_max' => 'nullable|numeric|min:0',
            'requires_shipping_address' => 'boolean', // Add this
        ]);

        $user = $request->user();

        $event = \App\Models\GiftExchangeEvent::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'end_date' => $validated['end_date'],
            'budget_max' => $validated['budget_max'] ?? null,
            'created_by' => $user->id,
            'requires_shipping_address' => $validated['requires_shipping_address'] ?? false, // Add this
        ]);

        \App\Models\GiftExchangeParticipant::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => 'accepted',
            'joined_at' => now(),
        ]);

        return redirect()->route('profile.events')->with('success', 'Event created successfully!');
    }

    // Show invitation page (Web)
    public function showInvitation(Request $request, $token)
    {
        $invitation = \App\Models\GiftExchangeInvitation::where('token', $token)->firstOrFail();

        if ($invitation->status !== 'pending') {
            return redirect()->route('login')->with('info', 'This invitation has already been responded to.');
        }

        $event = $invitation->event;

        // If user is not logged in, show the guest invitation page prompting sign-up/login
        $user = $request->user();
        if (!$user) {
            return view('gift-exchange.invitation-guest', compact('invitation', 'event'));
        }

        // Security check: Only the invited person (matching email) can view this invitation
        if ($user->email !== $invitation->email) {
            return redirect()->route('gift-exchange.invitationError');
        }

        return view('gift-exchange.invitation', compact('invitation', 'event'));
    }

    // Invite participants (Web)
    public function inviteParticipantsWeb(Request $request, $eventId)
    {
        $validated = $request->validate([
            'emails' => 'required|array|min:1',
            'emails.*' => 'required|email',
        ]);

        $event = \App\Models\GiftExchangeEvent::findOrFail($eventId);

        $invitations = [];
        foreach ($validated['emails'] as $email) {
            $token = bin2hex(random_bytes(16));
            $invitation = \App\Models\GiftExchangeInvitation::create([
                'event_id' => $event->id,
                'email' => $email,
                'token' => $token,
                'status' => 'pending',
                'sent_at' => now(),
            ]);
            \Mail::to($email)->send(new \App\Mail\GiftExchangeInvitationMail($invitation, $event));
            $invitations[] = $invitation;
        }

        return redirect()->route('profile.events')->with('success', 'Invitations sent successfully!');
    }

    // Respond to invitation (Web)
    public function respondToInvitationWeb(Request $request, $token)
    {
        $validated = $request->validate([
            'response' => 'required|in:accepted,declined',
        ]);

        $invitation = \App\Models\GiftExchangeInvitation::where('token', $token)->firstOrFail();

        if ($invitation->status !== 'pending') {
            return redirect()->route('login')->with('info', 'This invitation has already been responded to.');
        }

        // Security check: Only the invited person (matching email) can respond to this invitation
        $user = $request->user();
        if ($user && $user->email !== $invitation->email) {
            return redirect()->route('gift-exchange.invitationError');
        }

        // Update invitation status
        $invitation->status = $validated['response'];
        $invitation->responded_at = now();
        $invitation->save();

        if ($validated['response'] === 'accepted') {
            if ($user) {
                // Add as participant if not already
                $participant = \App\Models\GiftExchangeParticipant::firstOrCreate([
                    'event_id' => $invitation->event_id,
                    'user_id' => $user->id,
                ], [
                    'status' => 'accepted',
                    'joined_at' => now(),
                ]);
                
                // Log for debugging
                \Log::info('Participant created/found', [
                    'participant_id' => $participant->id,
                    'event_id' => $invitation->event_id,
                    'user_id' => $user->id,
                    'was_recently_created' => $participant->wasRecentlyCreated
                ]);
                
                if ($participant->needsShippingAddress()) {
                    return redirect()->route('gift-exchange.shipping-address', $invitation->event_id)
                                     ->with('info', 'Please provide your shipping address to complete your participation.');
                }
                
                return redirect()->route('profile.events')->with('success', 'Invitation accepted! You are now a participant.');
            } else {
                // TODO: Handle new user registration flow
                // For now, redirect to login with a message
                \Log::warning('User not authenticated when accepting invitation', ['token' => $token]);
                return redirect()->route('login')->with('info', 'Please log in or register to accept this invitation.');
            }
        } else {
            return redirect()->route('login')->with('info', 'Invitation declined.');
        }
    }

    /**
     * Accept an invitation from the authenticated user's dashboard.
     *
     * This endpoint is intended for in-app responses. It validates that the
     * authenticated user's email matches the invitation email and that the
     * invitation is still pending before marking it accepted and creating a participant.
     */
    public function acceptInvitationFromDashboard(Request $request, GiftExchangeInvitation $invitation)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login')->with('info', 'Please log in to respond to invitations.');
        }

        if ($user->email !== $invitation->email) {
            return redirect()->route('gift-exchange.invitationError');
        }

        if ($invitation->status !== 'pending') {
            return redirect()->route('profile.events')->with('info', 'This invitation has already been responded to.');
        }

        // Mark invitation accepted
        $invitation->status = 'accepted';
        $invitation->responded_at = now();
        $invitation->save();

        // Add participant record if not already present
        $participant = GiftExchangeParticipant::firstOrCreate([
            'event_id' => $invitation->event_id,
            'user_id' => $user->id,
        ], [
            'status' => 'accepted',
            'joined_at' => now(),
        ]);

        \Log::info('Participant created/found via dashboard', [
            'participant_id' => $participant->id,
            'event_id' => $invitation->event_id,
            'user_id' => $user->id,
            'was_recently_created' => $participant->wasRecentlyCreated
        ]);

        if ($participant->needsShippingAddress()) {
            return redirect()->route('gift-exchange.shipping-address', $invitation->event_id)
                             ->with('info', 'Please provide your shipping address to complete your participation.');
        }

        return redirect()->route('profile.events')->with('success', 'Invitation accepted! You are now a participant.');
    }

    /**
     * Decline an invitation from the authenticated user's dashboard.
     */
    public function declineInvitationFromDashboard(Request $request, GiftExchangeInvitation $invitation)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login')->with('info', 'Please log in to respond to invitations.');
        }

        if ($user->email !== $invitation->email) {
            return redirect()->route('gift-exchange.invitationError');
        }

        if ($invitation->status !== 'pending') {
            return redirect()->route('profile.events')->with('info', 'This invitation has already been responded to.');
        }

        $invitation->status = 'declined';
        $invitation->responded_at = now();
        $invitation->save();

        return redirect()->route('profile.events')->with('success', 'Invitation declined.');
    }
    /**
     * Show the shipping address form for a participant.
     */
    public function showShippingAddressForm(Request $request, GiftExchangeEvent $event)
    {
        $user = $request->user();

        // If the event does not require shipping addresses, redirect back politely.
        if (!method_exists($event, 'requiresShippingAddress') || !$event->requiresShippingAddress()) {
            return redirect()->route('gift-exchange.show', $event->id)
                             ->with('info', 'This event does not require shipping addresses.');
        }

        $participant = $event->participants()->where('user_id', $user->id)->first();
        if (!$participant || $participant->status !== 'accepted') {
            abort(403, 'You are not authorized to provide an address for this event.');
        }

        return view('gift-exchange.shipping-address', compact('event', 'participant'));
    }

    /**
     * Update the shipping address for a participant.
     *
     * Uses a dedicated FormRequest for robust validation and authorization.
     */
    public function updateShippingAddress(\App\Http\Requests\GiftExchangeShippingAddressRequest $request, GiftExchangeEvent $event)
    {
        $user = $request->user();
        $participant = $event->participants()->where('user_id', $user->id)->firstOrFail();

        $validated = $request->validated();

        try {
            $participant->shipping_address = $validated;
            $participant->shipping_address_completed_at = now();
            $participant->save();
        } catch (\Exception $e) {
            \Log::error('Failed to save shipping address', [
                'event_id' => $event->id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('gift-exchange.shipping-address', $event->id)
                             ->with('error', 'Failed to save shipping address. Please try again.');
        }

        return redirect()->route('gift-exchange.show', $event->id)->with('success', 'Shipping address saved successfully!');
    }

    /**
     * Show invitation error page.
     */
    public function invitationError()
    {
        return view('gift-exchange.invitation-error');
    }

    /**
     * Show the create event form.
     */
    public function showCreateForm(Request $request)
    {
        $user = $request->user();
        $events = \App\Models\GiftExchangeEvent::where('created_by', $user->id)->orderBy('end_date', 'desc')->get();

        return view('gift-exchange', [
            'events' => $events,
        ]);
    }

    /**
     * Delete a gift exchange event (owner-only).
     */
    public function destroy(Request $request, GiftExchangeEvent $event)
    {
        $user = $request->user();
        
        // Authorization: only event owner can delete
        if (!$user || $user->id !== $event->created_by) {
            abort(403, 'You are not authorized to delete this event.');
        }

        // Store event name for success message
        $eventName = $event->name;

        // Delete the event (cascade deletes will handle related records)
        $event->delete();

        return redirect()->route('profile.events')->with('success', "Event '{$eventName}' has been deleted successfully.");
    }
}