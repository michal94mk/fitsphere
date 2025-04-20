<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

/**
 * Middleware for setting the application language.
 * 
 * Sets the application language based on a hierarchy of sources:
 * 1. URL 'locale' parameter (highest priority)
 * 2. Language stored in session
 * 3. Default language from configuration (lowest priority)
 * 
 * With this middleware, the application consistently applies
 * the language chosen by the user.
 */
class SetLocale
{
    /**
     * Handles the incoming request and sets the application language.
     * 
     * Checks available language sources in priority order,
     * saves the selected language in the session, and sets
     * the application configuration accordingly.
     *
     * @param  \Illuminate\Http\Request  $request HTTP request object
     * @param  \Closure  $next Next function in the middleware chain
     * @return mixed Result of request processing
     */
    public function handle(Request $request, Closure $next)
    {
        // Initial locale value
        $locale = null;
        
        // Priority 1: URL parameter
        if ($request->has('locale')) {
            $locale = $request->query('locale');
            // Save in session for future requests
            Session::put('locale', $locale);
        } 
        // Priority 2: Value from session
        else if (Session::has('locale')) {
            $locale = Session::get('locale');
        } 
        // Priority 3: Default value
        else {
            $locale = config('app.locale', 'en');
        }
        
        // Validate supported language
        if (!in_array($locale, ['en', 'pl'])) {
            $locale = 'en';
        }
        
        // Set application language
        App::setLocale($locale);
        
        // Make sure Livewire components know the locale too
        Session::put('livewire_locale', $locale);
        
        return $next($request);
    }
} 