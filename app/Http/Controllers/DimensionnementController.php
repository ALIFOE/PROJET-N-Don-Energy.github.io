<?php

namespace App\Http\Controllers;

use App\Http\Requests\DimensionnementRequest;
use App\Mail\DimensionnementRequest as DimensionnementMail;
use App\Models\Dimensionnement;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class DimensionnementController extends Controller
{
    protected const STATUTS_VALIDES = ['en_attente', 'validé', 'refusé'];

    /**
     * Afficher le formulaire de dimensionnement.
     *
     * @return \Illuminate\View\View
     */
    public function showForm()
    {
        return view('dimensionnements.form');
    }

    /**
     * Afficher la liste des demandes de dimensionnement.
     */
    public function index()
    {
        try {
            $dimensionnements = Dimensionnement::with('user')
                ->where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('dimensionnements.index', compact('dimensionnements'));
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des dimensionnements : ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la récupération de vos demandes.');
        }
    }

    /**
     * Afficher le formulaire de création.
     */
    public function create()
    {
        return view('dimensionnements.create');
    }

    /**
     * Enregistrer une nouvelle demande.
     */
    public function store(DimensionnementRequest $request)
    {
        try {
            Log::info('Début de la création du dimensionnement', $request->validated());
            
            $dimensionnement = new Dimensionnement($request->validated());
            $dimensionnement->user_id = auth()->id();
            $dimensionnement->statut = 'en_attente';
            
            Log::info('Tentative de sauvegarde du dimensionnement');
            $dimensionnement->save();
            Log::info('Dimensionnement sauvegardé avec succès', ['id' => $dimensionnement->id]);

            try {
                Log::info('Tentative d\'envoi d\'email à : ' . $request->email);
                Mail::to($request->email)->send(new DimensionnementMail($dimensionnement));
                Log::info('Email envoyé avec succès');
            } catch (\Exception $mailException) {
                Log::error('Erreur lors de l\'envoi de l\'email : ' . $mailException->getMessage(), [
                    'exception' => $mailException,
                    'email' => $request->email
                ]);
            }

            return redirect()
                ->route('dashboard')
                ->with('success', 'Votre demande de dimensionnement a été enregistrée avec succès. Un email de confirmation vous a été envoyé.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du dimensionnement : ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'enregistrement de votre demande. Veuillez réessayer.');
        }
    }

    /**
     * Afficher une demande spécifique.
     */
    public function show(Dimensionnement $dimensionnement)
    {
        $this->authorize('view', $dimensionnement);
        return view('dimensionnements.show', compact('dimensionnement'));
    }

    /**
     * Afficher le formulaire de modification.
     */
    public function edit(Dimensionnement $dimensionnement)
    {
        $this->authorize('update', $dimensionnement);
        return view('dimensionnements.edit', compact('dimensionnement'));
    }

    /**
     * Mettre à jour une demande.
     */
    public function update(DimensionnementRequest $request, Dimensionnement $dimensionnement)
    {
        try {
            $this->authorize('update', $dimensionnement);
            
            if (!in_array($dimensionnement->statut, self::STATUTS_VALIDES)) {
                throw new \Exception('Statut de dimensionnement invalide');
            }

            $dimensionnement->update($request->validated());

            return redirect()
                ->route('dimensionnements.show', $dimensionnement)
                ->with('success', 'La demande a été mise à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du dimensionnement : ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour de votre demande. Veuillez réessayer.');
        }
    }

    /**
     * Supprimer une demande.
     */
    public function destroy(Dimensionnement $dimensionnement)
    {
        try {
            $this->authorize('delete', $dimensionnement);
            
            $dimensionnement->delete();

            return redirect()
                ->route('dimensionnements.index')
                ->with('success', 'La demande a été supprimée avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du dimensionnement : ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la suppression de votre demande.');
        }
    }
}
