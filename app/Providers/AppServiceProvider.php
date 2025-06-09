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
use App\Services\TranslationService;
use GuzzleHttp\Client;
use Laravel\Socialite\SocialiteServiceProvider;



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
        
        // Disable SSL verification for Windows development
        if ($this->app->environment('local') && strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Set global defaults for Guzzle
            $this->app->bind(Client::class, function () {
                return new Client([
                    'verify' => false,
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => 0,
                        CURLOPT_SSL_VERIFYHOST => 0,
                    ]
                ]);
            });
        }
        
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