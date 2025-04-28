<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Support\Facades\Event;
use App\Models\Trainer;
use App\Services\EmailService;
use App\Services\LogService;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Email Service as a singleton
        $this->app->singleton(EmailService::class, function ($app) {
            return new EmailService();
        });
        
        // Register Log Service as a singleton
        $this->app->singleton(LogService::class, function ($app) {
            return new LogService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(!app()->isProduction());
        
        // Clear existing listeners for the Registered event
        $events = $this->app['events'];
        $events->forget(Registered::class);
        
        // Register custom listener that handles both user types
        Event::listen(Registered::class, function (Registered $event) {
            // Select appropriate notification method based on user type
            // Only one method is called to prevent duplicate emails
            if ($event->user instanceof Trainer) {
                // For Trainers, use their class-specific method
                $event->user->sendEmailVerificationNotification();
            } else {
                // For regular users, use the standard handler
                (new SendEmailVerificationNotification)->handle($event);
            }
        });
    }
}