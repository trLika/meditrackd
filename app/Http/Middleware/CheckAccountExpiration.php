<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->expires_at && $user->expires_at->isPast()) {
                // Notifier les administrateurs (par rôle ou par nom)
                $admins = \App\Models\User::whereHas('roles', function($q) {
                    $q->whereIn('name', ['admin', 'administrateur']);
                })->orWhere('name', 'Administrateur')->get();
                
                \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\AccountExpiredAttempt($user));

                Auth::logout();
                
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')->with('error', 'Votre compte temporaire a expiré. Veuillez contacter l\'administrateur.');
            }
        }

        return $next($request);
    }
}
