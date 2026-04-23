<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Seul l'admin peut voir tous les utilisateurs
        if (!$user->hasRole('administrateur')) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $users = User::with('roles', 'services')->get();
        return response()->json($users);
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:administrateur,medecin,patient,stagiaire'
        ]);

        $user = Auth::user();
        
        // Seul l'admin peut créer des utilisateurs
        if (!$user->hasRole('administrateur')) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assigner le rôle
        $newUser->assignRole($request->role);

        return response()->json($newUser->load('roles'), 201);
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $authUser = Auth::user();
        
        // L'utilisateur peut voir son propre profil
        if ($authUser->id === $user->id) {
            return response()->json($user->load('roles', 'services'));
        }
        
        // L'admin peut voir tous les profils
        if ($authUser->hasRole('administrateur')) {
            return response()->json($user->load('roles', 'services'));
        }

        return response()->json(['message' => 'Non autorisé'], 403);
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $authUser = Auth::user();
        
        // L'utilisateur peut modifier son propre profil (sauf le rôle)
        if ($authUser->id === $user->id) {
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'password' => 'sometimes|string|min:8'
            ]);

            if ($request->has('password')) {
                $user->password = Hash::make($request->password);
            }
            
            if ($request->has('name')) {
                $user->name = $request->name;
            }
            
            $user->save();
            
            return response()->json($user->load('roles', 'services'));
        }
        
        // L'admin peut modifier tout
        if ($authUser->hasRole('administrateur')) {
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,' . $user->id,
                'password' => 'sometimes|string|min:8',
                'role' => 'sometimes|in:administrateur,medecin,patient,stagiaire'
            ]);

            if ($request->has('name')) $user->name = $request->name;
            if ($request->has('email')) $user->email = $request->email;
            if ($request->has('password')) $user->password = Hash::make($request->password);
            
            $user->save();

            // Mettre à jour le rôle si spécifié
            if ($request->has('role')) {
                $user->syncRoles([$request->role]);
            }
            
            return response()->json($user->load('roles', 'services'));
        }

        return response()->json(['message' => 'Non autorisé'], 403);
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        $authUser = Auth::user();
        
        // Seul l'admin peut supprimer des utilisateurs
        if (!$authUser->hasRole('administrateur')) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        // Empêcher la suppression de soi-même
        if ($authUser->id === $user->id) {
            return response()->json(['message' => 'Vous ne pouvez pas supprimer votre propre compte'], 422);
        }

        $user->delete();
        return response()->json(['message' => 'Utilisateur supprimé']);
    }
}
