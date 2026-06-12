<?php

namespace App\Providers;

use App\Listeners\StripeWebhookListener;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Cashier\Events\WebhookReceived;

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
        RateLimiter::for('login', function(Request $request){
            $email = (string) Str::lower($request->input('email'));

            return Limit::perMinute(5)->by($email.$request->ip());
        });

        Event::listen(WebhookReceived::class, StripeWebhookListener::class);
    }
}
