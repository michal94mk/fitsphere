<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function dashboard()
    {
        return view('admin.users.dashboard');
    }

    public function index(Request $request)
    {
        $role = $request->query('role', 'user'); // Domyślnie 'user'

        // Pobieranie użytkowników zgodnie z rolą i paginacja
        $users = User::where('role', $role)->paginate(10);

        return view('admin.users.index', compact('users', 'role'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users',
            'password'     => 'required|min:6|confirmed',
            'role'         => 'required|in:admin,user,trainer',
            // Walidacja dodatkowych pól, opcjonalnie tylko dla trenerów
            'specialization' => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|max:2048'
        ]);

        // Obsługa uploadu zdjęcia, jeśli przesłano
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('users', 'public');
        }

        User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'role'           => $request->role,
            'specialization' => $request->role === 'trainer' ? $request->specialization : null,
            'description'    => $request->role === 'trainer' ? $request->description : null,
            'image'          => $request->role === 'trainer' ? $imagePath : null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'role'         => 'required|in:admin,user,trainer',
            'specialization' => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|max:2048'
        ]);

        // Obsługa uploadu zdjęcia
        $imagePath = $user->image; // domyślnie pozostawiamy istniejące zdjęcie
        if ($request->hasFile('image')) {
            // Opcjonalne usunięcie starego zdjęcia, jeśli istnieje:
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $imagePath = $request->file('image')->store('users', 'public');
        }

        // Aktualizacja użytkownika wraz z dodatkowymi polami (tylko dla trenerów)
        $user->update([
            'name'           => $request->name,
            'email'          => $request->email,
            'role'           => $request->role,
            'specialization' => $request->role === 'trainer' ? $request->specialization : null,
            'description'    => $request->role === 'trainer' ? $request->description : null,
            'image'          => $request->role === 'trainer' ? $imagePath : null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.users.index')->with('error', 'Cannot delete an admin.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
