<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    /**
     * Any authenticated user can create a booking.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Only admin or the booking owner (when still pending) can update.
     */
    public function update(User $user, Booking $booking): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $booking->user_id === $user->id && $booking->status === 'pending';
    }

    /**
     * Only admin or the booking owner (when pending/cancelled) can delete.
     */
    public function delete(User $user, Booking $booking): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $booking->user_id === $user->id
            && in_array($booking->status, ['pending', 'cancelled']);
    }
}
