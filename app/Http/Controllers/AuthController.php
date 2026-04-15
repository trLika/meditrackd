<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Affiche la page de connexion
    public function showLogin() {
        return view('auth.login');
    }

    // Gère la tentative de connexion
    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Rediriger vers la page prévue ou vers le dashboard par défaut
            $redirectUrl = session('url.intended', route('dashboard'));
            return redirect($redirectUrl);
        }

        return back()->withErrors([
            'email' => 'Identifiants incorrects.',
        ]);
    }

    // Déconnexion
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('welcome'); // Redirige vers la page d'accueil après la déconnexion
    }
}
