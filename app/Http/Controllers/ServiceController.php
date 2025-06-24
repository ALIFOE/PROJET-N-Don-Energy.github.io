<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\DemandeService;
use App\Models\User;
use App\Mail\ServiceRequestConfirmationMail;
use App\Notifications\NewServiceRequestNotification;
use App\Notifications\ServiceRequestStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller 
{     
    public function __construct()
    {         
        $this->middleware('auth')->only(['submitRequest', 'showRequestForm']);     
    }      
    
    public function index()
    {         
        $services = Service::all();         
        return view('services.index', compact('services'));     
    }      
    
    public function show(Service $service)
    {         
        return view('services.show', compact('service'));     
    }      
    
    public function showRequestForm(Service $service)
    {         
        // Récupérer les champs supplémentaires du service
        $champs_supplementaires = $service->champs_supplementaires ?? [];
        return view('services.request-form', compact('service', 'champs_supplementaires'));     
    }
    
    public function submitRequest(Request $request, Service $service)     
    {         
        // Règles de validation de base
        $rules = [
            'nom' => 'required|string|max:255',
            'email' => 'required|email',
            'telephone' => 'required|string|max:20',
            'description' => 'required|string'
        ];

        // Ajouter des règles de validation pour les champs supplémentaires
        $champs_supplementaires = $service->champs_supplementaires ?? [];
        foreach ($champs_supplementaires as $champ) {
            $rule = $champ['required'] ? 'required' : 'nullable';
            
            switch ($champ['type']) {
                case 'email':
                    $rule .= '|email';
                    break;
                case 'number':
                    $rule .= '|numeric';
                    break;
                case 'file':
                    $rule .= '|file|mimes:pdf,doc,docx|max:2048';
                    break;
                case 'select':
                    $rule .= '|in:' . implode(',', $champ['options']);
                    break;
                default:
                    $rule .= '|string';
            }
            
            $rules['champs_supplementaires.' . $champ['name']] = $rule;
        }

        $validated = $request->validate($rules);

        $serviceRequest = new DemandeService();
        $serviceRequest->service_id = $service->id;
        $serviceRequest->user_id = auth()->id();
        $serviceRequest->nom = $validated['nom'];
        $serviceRequest->email = $validated['email'];
        $serviceRequest->telephone = $validated['telephone'];
        $serviceRequest->description = $validated['description'];
        $serviceRequest->details = $validated['description'];         // Sauvegarder les champs supplémentaires spécifiques au service
        if (isset($validated['champs_supplementaires'])) {
            $serviceRequest->champs_specifiques = $validated['champs_supplementaires'];
        }
        
        $serviceRequest->statut = 'en_attente';
        $serviceRequest->save();

        // Envoyer l'email de confirmation au client
        Mail::to($validated['email'])->send(new ServiceRequestConfirmationMail($serviceRequest));

        // Notifier les administrateurs
        User::where('role', 'admin')->get()->each(function($admin) use ($serviceRequest) {
            $admin->notify(new NewServiceRequestNotification($serviceRequest));
        });

        // Rediriger vers la même page avec un message de succès
        return redirect()->back()->with('success', 'Votre demande a été envoyée avec succès. Nous vous contacterons prochainement.');
    }

    public function requestDetails(DemandeService $request)
    {
        return response()->json($request->load('service'));
    }

    public function adminIndex()
    {
        $services = Service::withCount('demandes')->latest()->get();
        return view('admin.services.index', compact('services'));
    }

    public function adminCreate()
    {
        return view('admin.services.form');
    }

    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'champs_supplementaires' => 'nullable|array'
        ]);
        
        $service = new Service();
        $service->nom = $validated['nom'];
        $service->description = $validated['description'];
        
        if ($request->hasFile('image')) {
            $service->image = $request->file('image')->store('services', 'public');
        }
        
        if (isset($validated['champs_supplementaires'])) {
            // Traitement des options pour les champs de type select
            $champs = $validated['champs_supplementaires'];
            foreach ($champs as &$champ) {
                if ($champ['type'] === 'select' && isset($champ['options'])) {
                    // Convertir le texte des options en tableau
                    $champ['options'] = array_filter(explode("\n", $champ['options']));
                }
                // Convertir la valeur required en booléen
                $champ['required'] = $champ['required'] === '1';
            }
            $service->champs_supplementaires = $champs;
        }
        
        $service->save();
        return redirect()->route('admin.services.index')
            ->with('success', 'Service créé avec succès');
    }

    public function adminEdit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function adminUpdate(Request $request, Service $service)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'champs_supplementaires' => 'nullable|array'
        ]);

        $data = [
            'nom' => $validated['nom'],
            'description' => $validated['description']
        ];

        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        if (isset($validated['champs_supplementaires'])) {
            $data['champs_supplementaires'] = $validated['champs_supplementaires'];
        }

        $service->update($data);
        return redirect()->route('admin.services.index')
            ->with('success', 'Service mis à jour avec succès');
    }

    public function deleteRequest($id)
    {
        $request = \App\Models\DemandeService::find($id);
        if (!$request) {
            return back()->with('error', 'Demande introuvable.');
        }
        if (!in_array($request->statut, ['accepte', 'refuse'])) {
            return back()->with('error', 'Seules les demandes acceptées ou refusées peuvent être supprimées.');
        }
        $request->delete();
        return back()->with('success', 'Demande supprimée avec succès.');
    }

    public function adminRequests()
    {
        $requests = DemandeService::with(['service', 'user'])->latest()->get();
        $services = Service::all();
        return view('admin.services.requests', compact('requests', 'services'));
    }

    public function updateRequestStatus(Request $request, $id)
    {
        $serviceRequest = \App\Models\DemandeService::find($id);
        if (!$serviceRequest) {
            return back()->with('error', 'Demande introuvable.');
        }
        $validated = $request->validate([
            'statut' => ['required', 'in:en_attente,en_cours,accepte,refuse']
        ]);

        \Log::info('Statut reçu:', ['statut' => $validated['statut']]);
        $serviceRequest->update([
            'statut' => $validated['statut']
        ]);
        \Log::info('Statut après update:', ['statut' => $serviceRequest->fresh()->statut]);

        // Envoyer une notification au client si le statut a changé
        if ($serviceRequest->user) {
            $serviceRequest->user->notify(new ServiceRequestStatusChanged($serviceRequest));
        }

        return back()->with('success', 'Statut de la demande mis à jour avec succès.');
    }

    public function adminDestroy(Service $service)
    {
        try {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }

            $service->delete();
            return redirect()->route('admin.services.index')
                ->with('success', 'Service supprimé avec succès');
        } catch (\Exception $e) {
            return redirect()->route('admin.services.index')
                ->with('error', 'Impossible de supprimer ce service. Il est peut-être lié à des demandes existantes.');
        }
    }

    /**
     * Affiche une demande de service précise pour un service donné
     */
    public function showRequest(Service $service, \App\Models\DemandeService $serviceRequest)
    {
        return view('services.request-show', compact('service', 'serviceRequest'));
    }
}