<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

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

        if (Auth::attempt($credentials, $request->has('remember'))) {
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

    // --- MOT DE PASSE OUBLIÉ ---

    // Affiche le formulaire de demande de lien
    public function showForgotPassword() {
        return view('auth.forgot-password');
    }

    // Envoie le lien de réinitialisation
    public function sendResetLink(Request $request) {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    // Affiche le formulaire de réinitialisation
    public function showResetPassword($token) {
        return view('auth.reset-password', ['token' => $token]);
    }

    // Traite la réinitialisation du mot de passe
    public function resetPassword(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    // --- RÉINITIALISATION VIA QUESTION DE SÉCURITÉ ---

    public function showSecurityQuestionForm() {
        return view('auth.reset-security-step1');
    }

    public function verifyEmailForSecurity(Request $request) {
        $email = $request->input('email') ?? session('reset_email');
        
        if (!$email) {
            return redirect()->route('password.security.step1');
        }

        $user = \App\Models\User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.security.step1')->withErrors(['email' => 'Utilisateur introuvable.']);
        }

        if (!$user->security_question) {
            return redirect()->route('password.security.step1')->withErrors(['email' => 'Aucune question de sécurité n\'a été configurée pour ce compte.']);
        }

        // Sauvegarder l'email en session pour permettre le rafraîchissement de la page
        session(['reset_email' => $email]);

        return view('auth.reset-security-step2', compact('user'));
    }

    public function resetWithSecurityAnswer(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'security_answer' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (strtolower($user->security_answer) !== strtolower($request->security_answer)) {
            return back()->withErrors(['security_answer' => 'La réponse est incorrecte.']);
        }

        $user->forceFill([
            'password' => Hash::make($request->password)
        ])->setRememberToken(Str::random(60));

        $user->save();

        session()->forget('reset_email');

        return redirect()->route('login')->with('success', 'Votre mot de passe a été réinitialisé avec succès !');
    }
}
