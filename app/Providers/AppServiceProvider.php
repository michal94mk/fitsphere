<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Support\Facades\Event;
use App\Models\Trainer;

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
        Model::preventLazyLoading(!app()->isProduction());
        
        // Wyłączamy wszystkie istniejące listenery dla zdarzenia Registered
        $events = $this->app['events'];
        $events->forget(Registered::class);
        
        // Dodajemy tylko nasz własny listener, który obsługuje oba typy użytkowników
        Event::listen(Registered::class, function (Registered $event) {
            // Sprawdzamy typ użytkownika i wywołujemy odpowiednią metodę
            // Zawsze wywołujemy tylko jedną metodę, aby uniknąć duplikacji emaili
            if ($event->user instanceof Trainer) {
                // Dla Trenerów użyj ich własnej metody
                $event->user->sendEmailVerificationNotification();
            } else {
                // Dla zwykłych użytkowników, użyj standardowego handlera
                (new SendEmailVerificationNotification)->handle($event);
            }
        });
    }
}