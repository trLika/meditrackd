<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // Les admins ont accès à tout
        if ($user->hasRole('administrateur')) {
            return $next($request);
        }
        
        // Les patients n'ont accès qu'à leurs propres données
        if ($user->hasRole('patient')) {
            // Vérifier si le patient accède à ses propres données
            $patientId = $request->route('patient');
            if ($patientId) {
                $patient = \App\Models\Patient::find($patientId);
                if (!$patient || $patient->user_id !== $user->id) {
                    return response()->json(['message' => 'Non autorisé'], 403);
                }
            }
            return $next($request);
        }
        
        // Les médecins n'accèdent qu'aux patients de leur service
        if ($user->hasRole('medecin')) {
            $patientId = $request->route('patient');
            if ($patientId) {
                $patient = \App\Models\Patient::find($patientId);
                if (!$patient) {
                    return response()->json(['message' => 'Patient non trouvé'], 404);
                }
                
                // Vérifier que le patient est dans un service du médecin
                $userServices = $user->services->pluck('id');
                if (!$userServices->contains($patient->service_id)) {
                    return response()->json(['message' => 'Ce patient n\'est pas dans votre service'], 403);
                }
            }
            return $next($request);
        }
        
        // Les stagiaires ont un accès en lecture seule
        if ($user->hasRole('stagiaire')) {
            if ($request->method() !== 'GET') {
                return response()->json(['message' => 'Accès en lecture seule pour les stagiaires'], 403);
            }
            
            $patientId = $request->route('patient');
            if ($patientId) {
                $patient = \App\Models\Patient::find($patientId);
                if (!$patient) {
                    return response()->json(['message' => 'Patient non trouvé'], 404);
                }
                
                // Vérifier que le patient est dans un service du stagiaire
                $userServices = $user->services->pluck('id');
                if (!$userServices->contains($patient->service_id)) {
                    return response()->json(['message' => 'Ce patient n\'est pas dans votre service'], 403);
                }
            }
            return $next($request);
        }
        
        return response()->json(['message' => 'Non autorisé'], 403);
    }
}
