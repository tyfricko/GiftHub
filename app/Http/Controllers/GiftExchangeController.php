<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
        $participants = $event->participants()->where('status', 'accepted')->with('user.wishes')->get();

        if ($participants->count() < 2) {
            return response()->json(['error' => 'At least 2 participants are required for assignment.'], 400);
        }

        // Shuffle participants for random assignment
        $givers = $participants->shuffle()->values();
        $recipients = $givers->slice(1)->concat($givers->slice(0, 1))->values();

        $assignments = [];
        foreach ($givers as $i => $giver) {
            $recipient = $recipients[$i];
            // Prevent self-assignment (should not happen with above logic)
            if ($giver->id === $recipient->id) {
                return response()->json(['error' => 'Assignment failed due to self-assignment.'], 500);
            }
            $assignment = \App\Models\GiftAssignment::create([
                'event_id' => $event->id,
                'giver_id' => $giver->id,
                'recipient_id' => $recipient->id,
                'assigned_at' => now(),
            ]);
            // TODO: Send notification email to $giver->user->email with recipient info (stubbed)
            // Send assignment notification email to giver
            Mail::to($giver->user->email)->send(
                new GiftAssignmentNotificationMail($event, $giver, $recipient)
            );
            $assignments[] = $assignment;
        }

        return response()->json([
            'assignments' => $assignments,
        ]);
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
    public function createEventWeb(Request $request)
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

        \App\Models\GiftExchangeParticipant::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => 'accepted',
            'joined_at' => now(),
        ]);

        return redirect()->route('gift-exchange.dashboard')->with('success', 'Event created successfully!');
    }

    // Show invitation page (Web)
    public function showInvitation(Request $request, $token)
    {
        $invitation = \App\Models\GiftExchangeInvitation::where('token', $token)->firstOrFail();

        if ($invitation->status !== 'pending') {
            return redirect()->route('login')->with('info', 'This invitation has already been responded to.');
        }

        $event = $invitation->event;

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

        return redirect()->route('gift-exchange.dashboard')->with('success', 'Invitations sent successfully!');
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

        // Update invitation status
        $invitation->status = $validated['response'];
        $invitation->responded_at = now();
        $invitation->save();

        if ($validated['response'] === 'accepted') {
            $user = $request->user();
            if ($user) {
                // Add as participant if not already
                \App\Models\GiftExchangeParticipant::firstOrCreate([
                    'event_id' => $invitation->event_id,
                    'user_id' => $user->id,
                ], [
                    'status' => 'accepted',
                    'joined_at' => now(),
                ]);
                return redirect()->route('gift-exchange.dashboard')->with('success', 'Invitation accepted! You are now a participant.');
            } else {
                // TODO: Handle new user registration flow
                // For now, redirect to login with a message
                return redirect()->route('login')->with('info', 'Please log in or register to accept this invitation.');
            }
        } else {
            return redirect()->route('login')->with('info', 'Invitation declined.');
        }
    }
}