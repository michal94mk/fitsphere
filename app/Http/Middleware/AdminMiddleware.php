<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
        // Check if the logged-in user has the 'admin' role
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            if ($user instanceof User && $user->isAdmin()) {
                return $next($request);
            }
        }
        
        return redirect()->route('home')->with('error', __('middleware.admin_access_required'));
    }
}
