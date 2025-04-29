<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TechnicianApplicationMail;

class TechnicianController extends Controller
{
    // Affiche le formulaire de demande d'emploi
    public function showForm()
    {
        return view('technician-form');
    }

    // Traite le formulaire de demande d'emploi
    public function submitForm(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Envoi de l'email à l'administration
        Mail::to('admin@example.com')->send(new TechnicianApplicationMail($request->all()));

        return redirect()->route('technician.form')->with('success', 'Votre demande a été envoyée avec succès.');
    }
}
