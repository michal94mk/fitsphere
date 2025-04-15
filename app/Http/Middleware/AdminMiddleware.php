<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Sprawdź czy zalogowany użytkownik ma rolę 'admin'
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }
        
        return redirect()->route('home')->with('error', 'Brak dostępu do panelu administratora.');
    }
}
