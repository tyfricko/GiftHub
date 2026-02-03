<?php

namespace App\Services;

use App\Models\User;

class UserProfileService
{
    /**
     * Update user profile.
     */
    public function updateProfile(User $user, array $data): User
    {
        // Handle avatar upload if provided
        if (isset($data['avatar']) && $data['avatar'] instanceof \Illuminate\Http\UploadedFile) {
            $avatarPath = $data['avatar']->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        // Update password if provided
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        // Update user profile (excluding password fields from mass assignment)
        $user->update(collect($data)->except(['current_password', 'password_confirmation'])->toArray());

        return $user->fresh();
    }

    /**
     * Get the user's pending invitations count.
     */
    public function getPendingInvitationsCount(User $user): int
    {
        return \App\Models\GiftExchangeInvitation::where('email', $user->email)
            ->where('status', 'pending')
            ->count();
    }

    /**
     * Get the user's pending invitations.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPendingInvitations(User $user)
    {
        return \App\Models\GiftExchangeInvitation::where('email', $user->email)
            ->where('status', 'pending')
            ->with('event')
            ->orderByDesc('created_at')
            ->get();
    }
}
