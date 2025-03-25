<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function showForm()
    {
        return view('contact');
    }

    public function handleForm(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required',
        ]);

        $data = $request->only('name', 'email', 'message');
        Mail::send('emails.contact', ['data' => $data], function ($message) use ($data) {
            $message->to('admin@example.com')
                    ->subject('Nowa wiadomość kontaktowa');
        });        

        return back()->with('success', 'Twoja wiadomość została wysłana!');
    }
}
