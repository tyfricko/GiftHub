<?php

namespace Tests\Feature;

use App\Models\GiftExchangeEvent;
use App\Models\GiftExchangeInvitation;
use App\Models\GiftExchangeParticipant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GiftExchangeShippingFlowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function invite_accept_provide_address_and_assign_flow()
    {
        // Create owner and participant users
        $owner = User::factory()->create([
            'username' => 'owner',
            'fullname' => 'Owner User',
            'email' => 'owner@example.test',
            'password' => bcrypt('password'),
        ]);

        $participantUser = User::factory()->create([
            'username' => 'participant',
            'fullname' => 'Participant User',
            'email' => 'p@example.test',
            'password' => bcrypt('password'),
        ]);

        // Owner creates an event that requires shipping addresses
        $this->actingAs($owner)
            ->post(route('gift-exchange.create'), [
                'name' => 'Test Exchange',
                'description' => 'Testing shipping flow',
                'end_date' => now()->addDays(7)->format('Y-m-d\TH:i'),
                'budget_max' => 50,
                'requires_shipping_address' => 1,
            ])
            ->assertRedirect(route('profile.events'));

        $event = GiftExchangeEvent::first();
        $this->assertNotNull($event);
        $this->assertTrue((bool) $event->requires_shipping_address);

        // Owner invites participant by email via the web invite route
        $this->actingAs($owner)
            ->post(route('gift-exchange.invite', $event->id), [
                'emails' => [$participantUser->email],
            ])
            ->assertRedirect(route('profile.events'));

        $invitation = GiftExchangeInvitation::where('email', $participantUser->email)->first();
        $this->assertNotNull($invitation);
        $this->assertEquals('pending', $invitation->status);

        // Participant responds to invitation (authenticated)
        $this->actingAs($participantUser)
            ->post(route('gift-exchange.respondToInvitationWeb', $invitation->token), [
                'response' => 'accepted',
            ])
            ->assertRedirect(route('gift-exchange.shipping-address', $event->id));

        // Ensure participant record exists and needs shipping address
        $participant = GiftExchangeParticipant::where('event_id', $event->id)
            ->where('user_id', $participantUser->id)
            ->first();

        $this->assertNotNull($participant);
        $this->assertEquals('accepted', $participant->status);
        $this->assertTrue($participant->needsShippingAddress());

        // Participant submits shipping address
        $this->actingAs($participantUser)
            ->post(route('gift-exchange.shipping-address.update', $event->id), [
                'full_name' => 'Participant Name',
                'address_line_1' => '123 Test St',
                'address_line_2' => 'Unit 5',
                'city' => 'Testville',
                'state_province' => 'Test State',
                'postal_code' => '12345',
                'country' => 'Testland',
                'phone' => '123456789',
                'delivery_notes' => 'Leave at door',
            ])
            ->assertRedirect(route('gift-exchange.show', $event->id));

        $participant->refresh();
        $this->assertNotNull($participant->shipping_address_completed_at);
        $this->assertTrue($participant->hasShippingAddress());

        // Create a second participant (owner as another participant) so assignment can run
        // Owner is already a participant (created on event creation). Confirm count >=2
        $participants = $event->participants()->where('status', 'accepted')->get();
        $this->assertTrue($participants->count() >= 2);

        // Ensure the owner participant also has a shipping address (simulate owner provided it),
        // otherwise assignment will be blocked when the event requires shipping addresses.
        $ownerParticipant = GiftExchangeParticipant::where('event_id', $event->id)
            ->where('user_id', $owner->id)
            ->first();
        if ($ownerParticipant && ! $ownerParticipant->hasShippingAddress()) {
            $ownerParticipant->shipping_address = [
                'full_name' => $owner->name ?? $owner->fullname ?? 'Owner',
                'address_line_1' => 'Owner Address 1',
                'city' => 'OwnerCity',
                'postal_code' => '00000',
                'country' => 'Ownerland',
            ];
            $ownerParticipant->shipping_address_completed_at = now();
            $ownerParticipant->save();
        }

        // Owner triggers assignments
        $this->actingAs($owner)
            ->post(route('gift-exchange.assign-gifts', $event->id))
            ->assertRedirect(route('gift-exchange.show', $event->id));

        // Assert assignments were created
        $this->assertDatabaseCount('gift_assignments', $event->assignments()->count() ?: 1);
        $this->assertTrue($event->assignments()->exists());

        // Ensure every assignment links to valid giver and recipient
        foreach ($event->assignments as $assignment) {
            $this->assertNotNull($assignment->giver_id);
            $this->assertNotNull($assignment->recipient_id);
            $this->assertNotEquals($assignment->giver_id, $assignment->recipient_id);
        }
    }
}
