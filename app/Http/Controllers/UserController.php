<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('services')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $services = Service::all();
        return view('admin.users.create', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,medecin,secretaire',
            'services' => 'array|exists:services,id'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Assigner le rôle
        $user->assignRole($request->role);

        // Synchroniser les services si c'est un médecin
        if ($request->role === 'medecin' && $request->has('services')) {
            $user->services()->sync($request->services);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $services = Service::all();
        return view('admin.users.edit', compact('user', 'services'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Synchroniser les services sélectionnés (many-to-many)
        $user->services()->sync($request->input('services', []));

        return redirect()->route('admin.users.index')->with('success', 'Médecin mis à jour !');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Empêcher la suppression de soi-même
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // Détacher les services avant suppression
        $user->services()->detach();
        
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
}
