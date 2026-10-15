<?php

namespace App\Providers;

use App\Models\Booking;
use App\Policies\BookingPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Booking Policy
        Gate::policy(Booking::class, BookingPolicy::class);

        // Gate for creating a booking (any authenticated user)
        Gate::define('create-booking', function ($user) {
            return true;
        });
    }
}
