<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Mail\ContactFormMail;
use App\Mail\ContactFormConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Events\ClientActivity;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'sujet' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Création du message de contact dans la base de données
        $contactData = [
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'sujet' => $validated['sujet'],
            'message' => $validated['message'],
            'statut' => 'non_lu'
        ];

        // Ajouter le téléphone seulement s'il est présent
        if (isset($validated['telephone'])) {
            $contactData['telephone'] = $validated['telephone'];
        }        
        $contact = Contact::create($contactData);

        // Envoi de l'email à l'administrateur
        Mail::to(config('mail.from.address'))->send(new ContactFormMail($validated));
        
        // Envoi de l'email de confirmation au client
        Mail::to($validated['email'])->send(new ContactFormConfirmationMail($validated));

        // Déclencher l'événement pour notifier les administrateurs
        event(new ClientActivity('contact_form', [
            'name' => $validated['nom'],
            'email' => $validated['email'],
            'subject' => $validated['sujet'],
            'id' => $contact->id
        ]));

        // Redirection avec message de succès
        return redirect()->back()->with('success', 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');
    }

    public function admin()
    {
        // Récupérer tous les messages, les plus récents en premier
        $messages = Contact::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.contacts', compact('messages'));
    }

    public function markAsRead($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update(['statut' => 'lu']);
        return redirect()->back()->with('success', 'Message marqué comme lu');
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return redirect()->back()->with('success', 'Message supprimé avec succès');
    }
}
