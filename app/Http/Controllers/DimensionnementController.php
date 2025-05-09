<?php

namespace App\Http\Controllers;

use App\Http\Requests\DimensionnementRequest;
use App\Mail\DimensionnementRequest as DimensionnementMail;
use App\Models\Dimensionnement;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DimensionnementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }    protected const STATUTS_VALIDES = ['en_attente', 'validé', 'refusé'];

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
    }    /**
     * Afficher le formulaire de création.
     */
    public function create()
    {
        return view('dimensionnements.create');
    }

    /**
     * Enregistrer une nouvelle demande.
     */    public function store(DimensionnementRequest $request)
    {
        try {
            DB::beginTransaction();
            
            // Validation et préparation des données
            $validatedData = $request->validated();
            $validatedData['user_id'] = auth()->id();
            $validatedData['statut'] = 'en_attente';
            
            // Création du dimensionnement
            $dimensionnement = Dimensionnement::create($validatedData);
            
            if (!$dimensionnement) {
                throw new \Exception('Échec de la création du dimensionnement');
            }
            
            DB::commit();
            
            // Envoi de l'email
            try {
                Mail::to($request->email)->send(new DimensionnementMail($dimensionnement));
            } catch (\Exception $mailException) {
                Log::error('Erreur lors de l\'envoi de l\'email de confirmation', [
                    'error' => $mailException->getMessage(),
                    'email' => $request->email
                ]);
                // On continue même si l'email échoue
            }

            // Message de succès
            session()->flash('dimensionnement_success', 'Votre demande de dimensionnement a été enregistrée avec succès.');
            
            return redirect()->route('dashboard');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur lors de la création du dimensionnement', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de l\'enregistrement de votre demande. Veuillez réessayer.']);
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

    /**
     * Traiter la soumission du formulaire de dimensionnement.
     */
    public function submit(DimensionnementRequest $request)
    {
        return $this->store($request);
    }
}
