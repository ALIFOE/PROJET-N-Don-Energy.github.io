<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::orderBy('created_at', 'desc')->paginate(10);
        return view('messages.index', compact('messages'));
    }

    public function show(Message $message)
    {
        return view('messages.show', compact('message'));
    }

    public function edit(Message $message)
    {
        return view('messages.edit', compact('message'));
    }

    public function update(Request $request, Message $message)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string'
        ]);

        $message->update($validated);

        return redirect()->route('messages.index')
            ->with('success', 'Message mis à jour avec succès.');
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return redirect()->route('messages.index')
            ->with('success', 'Message supprimé avec succès.');
    }
}
