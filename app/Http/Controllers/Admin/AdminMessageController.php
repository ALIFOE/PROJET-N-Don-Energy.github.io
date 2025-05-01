<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminMessage;
use App\Models\MessageAttachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminMessageController extends Controller
{
    public function index()
    {
        $messages = AdminMessage::where('sender_id', auth()->id())
            ->with(['recipient', 'attachments'])
            ->latest()
            ->paginate(20);

        return view('admin.messages.index', compact('messages'));
    }

    public function create()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        return view('admin.messages.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'attachments.*' => 'file|max:10240' // Max 10MB par fichier
        ]);

        $message = AdminMessage::create([
            'sender_id' => auth()->id(),
            'recipient_id' => $request->recipient_id,
            'subject' => $request->subject,
            'content' => $request->content
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('message-attachments');
                
                MessageAttachment::create([
                    'admin_message_id' => $message->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize()
                ]);
            }
        }

        return redirect()->route('admin.messages.index')
            ->with('success', 'Message envoyé avec succès');
    }

    public function show(AdminMessage $message)
    {
        return view('admin.messages.show', compact('message'));
    }

    public function destroy(AdminMessage $message)
    {
        // Supprimer les fichiers attachés
        foreach ($message->attachments as $attachment) {
            Storage::delete($attachment->file_path);
            $attachment->delete();
        }

        $message->delete();

        return redirect()->route('admin.messages.index')
            ->with('success', 'Message supprimé avec succès');
    }
}
