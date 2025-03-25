<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        // Sprawdzenie, czy email już istnieje
        if (Subscriber::where('email', $request->email)->exists()) {
            return back()->with('error', 'Ten e-mail jest już zapisany do newslettera.');
        }

        // Dodanie nowej subskrypcji
        Subscriber::create(['email' => $request->email]);

        return back()->with('success', 'Zostałeś subskrybentem!');
    }
}
