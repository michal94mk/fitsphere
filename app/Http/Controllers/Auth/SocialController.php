<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use GuzzleHttp\Client;

class SocialController
{
    /**
     * Redirect to social provider
     */
    public function redirect()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Problem z przekierowaniem do Google. Spróbuj ponownie.');
        }
    }

    /**
     * Handle callback from social provider
     */
    public function callback()
    {
        try {
            // Force disable SSL verification for Windows development
            if (config('app.env') === 'local' && strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // Set all possible curl options to disable SSL
                putenv('CURLOPT_SSL_VERIFYPEER=0');
                putenv('CURLOPT_SSL_VERIFYHOST=0');
                ini_set('curl.cainfo', '');
                ini_set('openssl.cafile', '');
                
                // Configure Guzzle to not verify SSL
                config(['services.google.guzzle' => [
                    'verify' => false,
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => 0,
                        CURLOPT_SSL_VERIFYHOST => 0,
                    ]
                ]]);
            }
            
            $socialUser = Socialite::driver('google')->user();
            
            $user = User::firstOrCreate(
                [
                    'provider' => 'google',
                    'provider_id' => $socialUser->getId()
                ],
                [
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'email_verified_at' => now(),
                    'password' => null,
                ]
            );

            Auth::login($user);
            
            // Add success message for Google users
            session()->flash('success', __('common.login_success'));
            
            return redirect()->route('home');
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Problem z logowaniem Google. Spróbuj ponownie.');
        }
    }
}
