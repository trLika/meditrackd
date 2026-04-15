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
}
