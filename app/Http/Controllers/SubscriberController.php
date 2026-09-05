<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function create()
    {
        return view('updates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:150'],
        ]);

        Subscriber::firstOrCreate(['email' => $validated['email']]);

        return back()->with('success', "Thanks — you're on the list. We'll be in touch as the network grows.");
    }
}
