<?php

namespace App\Http\Controllers;

use App\Http\Requests\GiftExchangeCreateRequest;
use App\Http\Requests\GiftExchangeInvitationRequest;
use App\Http\Requests\GiftExchangeUpdateRequest;
use App\Models\GiftExchangeEvent;
use App\Models\GiftExchangeInvitation;
use App\Models\GiftExchangeParticipant;
use App\Services\GiftExchangeManagementService;
use Illuminate\Http\Request;

class GiftExchangeController extends Controller
{
    protected GiftExchangeManagementService $service;

    public function __construct(GiftExchangeManagementService $service)
    {
        $this->service = $service;
    }

    // 1. Create a new gift exchange event (API)
    public function createEvent(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'end_date' => 'required|date|after:now',
            'budget_max' => 'nullable|numeric|min:0',
        ]);

        $user = $request->user();
        $event = $this->service->createEvent($validated, $user->id);

        $participant = GiftExchangeParticipant::create([
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

    // 2. Invite participants by email (API)
    public function inviteParticipants(Request $request, $eventId): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'emails' => 'required|array|min:1',
            'emails.*' => 'required|email',
        ]);

        $event = GiftExchangeEvent::findOrFail($eventId);
        $invitations = $this->service->sendInvitations($event, $validated['emails']);

        return response()->json([
            'invitations' => $invitations,
        ], 201);
    }

    // 3. Respond to invitation (API)
    public function respondToInvitation(Request $request, $token): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'response' => 'required|in:accepted,declined',
        ]);

        $invitation = GiftExchangeInvitation::where('token', $token)->firstOrFail();

        // Update invitation status
        $invitation->status = $validated['response'];
        $invitation->responded_at = now();
        $invitation->save();

        $participant = null;
        $userId = null;

        if ($validated['response'] === 'accepted') {
            $user = $request->user();
            if ($user) {
                $userId = $user->id;
                $participant = $this->service->respondToInvitation($invitation, $validated['response'], $userId);
            }
        }

        return response()->json([
            'invitation' => $invitation,
            'participant' => $participant,
        ]);
    }

    // 4. Get participants for an event (API)
    public function getParticipants($eventId): \Illuminate\Http\JsonResponse
    {
        $event = GiftExchangeEvent::findOrFail($eventId);
        $participants = $event->participants()->with('user')->get();

        return response()->json([
            'participants' => $participants,
        ]);
    }

    // 5. Assign gifts (Secret Santa style)
    public function assignGifts(Request $request, $eventId): \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $event = GiftExchangeEvent::findOrFail($eventId);

        // Authorization: only event owner may trigger assignments
        $user = $request->user();
        if (! $user || $user->id !== $event->created_by) {
            return response()->json(['error' => 'Unauthorized. Only the event owner can assign gifts.'], 403);
        }

        try {
            $assignments = $this->service->assignGifts($event);

            return redirect()->route('gift-exchange.show', $event->id)
                ->with('success', 'Gift assignments created successfully!');
        } catch (\Exception $e) {
            \Log::error('Gift assignment transaction failed', [
                'event_id' => $event->id,
                'error' => $e->getMessage(),
            ]);

            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['error' => 'Assignment failed: '.$e->getMessage()], 500);
            }

            return redirect()->route('gift-exchange.show', $event->id)
                ->with('error', 'Assignment failed: '.$e->getMessage());
        }
    }

    // 6. Get assignments for an event (API)
    public function getAssignments($eventId): \Illuminate\Http\JsonResponse
    {
        $event = GiftExchangeEvent::findOrFail($eventId);
        $assignments = $event->assignments()
            ->with(['giver.user', 'recipient.user'])
            ->get();

        return response()->json([
            'assignments' => $assignments,
        ]);
    }

    // Gift Exchange Dashboard (Web)
    public function dashboard(Request $request): \Illuminate\View\View
    {
        $user = $request->user();
        $events = GiftExchangeEvent::where('created_by', $user->id)->orderBy('end_date', 'desc')->get();

        return view('profile-events', [
            'events' => $events,
        ]);
    }

    /**
     * Show a specific gift exchange event dashboard (Web).
     */
    public function show(Request $request, GiftExchangeEvent $event): \Illuminate\View\View
    {
        // Eager load useful relations
        $event = $this->service->loadEventRelations($event);

        $participants = $event->participants()->with('user')->get();
        $invitations = $event->invitations()->get();

        // Log for debugging
        \Log::info('Event show data', [
            'event_id' => $event->id,
            'participants_count' => $participants->count(),
            'participants_data' => $participants->map(function ($p) {
                return [
                    'id' => $p->id,
                    'user_id' => $p->user_id,
                    'status' => $p->status,
                    'user_email' => $p->user->email ?? 'N/A',
                ];
            }),
            'invitations_count' => $invitations->count(),
            'invitations_data' => $invitations->map(function ($i) {
                return [
                    'id' => $i->id,
                    'email' => $i->email,
                    'status' => $i->status,
                ];
            }),
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

    /**
     * Show edit form for an event (owner-only).
     */
    public function edit(Request $request, GiftExchangeEvent $event): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        if (! $user || $user->id !== $event->created_by) {
            abort(403, 'You are not authorized to edit this event.');
        }

        return view('gift-exchange.edit', [
            'event' => $event,
        ]);
    }

    /**
     * Update an existing event (owner-only).
     */
    public function update(GiftExchangeUpdateRequest $request, GiftExchangeEvent $event): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        $this->service->updateEvent($event, $validated);

        return redirect()->route('gift-exchange.show', $event->id)->with('success', 'Event updated successfully!');
    }

    /**
     * Handle Event Creation (Web).
     */
    public function createEventWeb(GiftExchangeCreateRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        $event = $this->service->createEvent($validated, $user->id);

        return redirect()->route('profile.events')->with('success', 'Event created successfully!');
    }

    // Show invitation page (Web)
    public function showInvitation(Request $request, $token): \Illuminate\View\View
    {
        $invitation = GiftExchangeInvitation::where('token', $token)->firstOrFail();

        if ($invitation->status !== 'pending') {
            return redirect()->route('login')->with('info', 'This invitation has already been responded to.');
        }

        $event = $invitation->event;

        // If user is not logged in, show the guest invitation page prompting sign-up/login
        $user = $request->user();
        if (! $user) {
            return view('gift-exchange.invitation-guest', compact('invitation', 'event'));
        }

        // Security check: Only the invited person (matching email) can view this invitation
        if ($user->email !== $invitation->email) {
            return redirect()->route('gift-exchange.invitationError');
        }

        return view('gift-exchange.invitation', compact('invitation', 'event'));
    }

    // Invite participants (Web)
    public function inviteParticipantsWeb(GiftExchangeInvitationRequest $request, $eventId): \Illuminate\Http\RedirectResponse
    {
        $event = GiftExchangeEvent::findOrFail($eventId);
        $this->service->sendInvitations($event, $request->input('emails', []));

        return redirect()->route('profile.events')->with('success', 'Invitations sent successfully!');
    }

    // Respond to invitation (Web)
    public function respondToInvitationWeb(Request $request, $token): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'response' => 'required|in:accepted,declined',
        ]);

        $invitation = GiftExchangeInvitation::where('token', $token)->firstOrFail();

        if ($invitation->status !== 'pending') {
            return redirect()->route('login')->with('info', 'This invitation has already been responded to.');
        }

        // Security check: Only the invited person (matching email) can respond to this invitation
        $user = $request->user();
        if ($user && $user->email !== $invitation->email) {
            return redirect()->route('gift-exchange.invitationError');
        }

        $this->service->respondToInvitation($invitation, $validated['response'], $user?->id);

        if ($validated['response'] === 'accepted') {
            if ($user) {
                $participant = GiftExchangeParticipant::where('event_id', $invitation->event_id)
                    ->where('user_id', $user->id)
                    ->first();

                // Log for debugging
                if ($participant) {
                    \Log::info('Participant found', [
                        'participant_id' => $participant->id,
                        'event_id' => $invitation->event_id,
                        'user_id' => $user->id,
                    ]);
                }

                if ($participant && $participant->needsShippingAddress()) {
                    return redirect()->route('gift-exchange.shipping-address', $invitation->event_id)
                        ->with('info', 'Please provide your shipping address to complete your participation.');
                }

                return redirect()->route('profile.events')->with('success', 'Invitation accepted! You are now a participant.');
            } else {
                // Handle new user registration flow
                \Log::warning('User not authenticated when accepting invitation', ['token' => $token]);

                return redirect()->route('login')->with('info', 'Please log in or register to accept this invitation.');
            }
        } else {
            return redirect()->route('login')->with('info', 'Invitation declined.');
        }
    }

    /**
     * Accept an invitation from the authenticated user's dashboard.
     */
    public function acceptInvitationFromDashboard(Request $request, GiftExchangeInvitation $invitation): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login')->with('info', 'Please log in to respond to invitations.');
        }

        if ($user->email !== $invitation->email) {
            return redirect()->route('gift-exchange.invitationError');
        }

        if ($invitation->status !== 'pending') {
            return redirect()->route('profile.events')->with('info', 'This invitation has already been responded to.');
        }

        $this->service->respondToInvitation($invitation, 'accepted', $user->id);

        $participant = GiftExchangeParticipant::where('event_id', $invitation->event_id)
            ->where('user_id', $user->id)
            ->first();

        \Log::info('Participant created/found via dashboard', [
            'participant_id' => $participant?->id,
            'event_id' => $invitation->event_id,
            'user_id' => $user->id,
        ]);

        if ($participant && $participant->needsShippingAddress()) {
            return redirect()->route('gift-exchange.shipping-address', $invitation->event_id)
                ->with('info', 'Please provide your shipping address to complete your participation.');
        }

        return redirect()->route('profile.events')->with('success', 'Invitation accepted! You are now a participant.');
    }

    /**
     * Decline an invitation from the authenticated user's dashboard.
     */
    public function declineInvitationFromDashboard(Request $request, GiftExchangeInvitation $invitation): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
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
    public function showShippingAddressForm(Request $request, GiftExchangeEvent $event): \Illuminate\Http\RedirectResponse|\Illuminate\View\View
    {
        $user = $request->user();

        // If the event does not require shipping addresses, redirect back politely.
        if (! method_exists($event, 'requiresShippingAddress') || ! $event->requiresShippingAddress()) {
            return redirect()->route('gift-exchange.show', $event->id)
                ->with('info', 'This event does not require shipping addresses.');
        }

        $participant = $event->participants()->where('user_id', $user->id)->first();
        if (! $participant || $participant->status !== 'accepted') {
            abort(403, 'You are not authorized to provide an address for this event.');
        }

        return view('gift-exchange.shipping-address', compact('event', 'participant'));
    }

    /**
     * Update the shipping address for a participant.
     */
    public function updateShippingAddress(\App\Http\Requests\GiftExchangeShippingAddressRequest $request, GiftExchangeEvent $event): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $participant = $event->participants()->where('user_id', $user->id)->firstOrFail();

        $validated = $request->validated();

        try {
            $this->service->updateShippingAddress($participant, $validated);
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
    public function invitationError(): \Illuminate\View\View
    {
        return view('gift-exchange.invitation-error');
    }

    /**
     * Show the create event form.
     */
    public function showCreateForm(Request $request): \Illuminate\View\View
    {
        return view('gift-exchange.create');
    }

    /**
     * Delete a gift exchange event (owner-only).
     */
    public function destroy(Request $request, GiftExchangeEvent $event): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        // Authorization: only event owner can delete
        if (! $user || $user->id !== $event->created_by) {
            abort(403, 'You are not authorized to delete this event.');
        }

        // Store event name for success message
        $eventName = $event->name;

        // Delete the event (cascade deletes will handle related records)
        $this->service->deleteEvent($event);

        return redirect()->route('profile.events')->with('success', "Event '{$eventName}' has been deleted successfully.");
    }
}
