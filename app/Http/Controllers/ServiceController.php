<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\DemandeService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['submitRequest']);
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

    public function submitRequest(Request $request, Service $service)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email',
            'telephone' => 'required|string|max:20',
            'description' => 'required|string'
        ]);

        $serviceRequest = new DemandeService();
        $serviceRequest->service_id = $service->id;
        $serviceRequest->user_id = auth()->id();
        $serviceRequest->nom = $validated['nom'];
        $serviceRequest->email = $validated['email'];
        $serviceRequest->telephone = $validated['telephone'];
        $serviceRequest->details = $validated['description'];
        $serviceRequest->statut = 'en_attente';
        $serviceRequest->save();

        return redirect()->route('services.show', $service)
            ->with('success', 'Votre demande a été envoyée avec succès.');
    }

    public function adminIndex()
    {
        $services = Service::all();
        return view('admin.services.index', compact('services'));
    }

    public function adminCreate()
    {
        return view('admin.services.create');
    }

    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        Service::create($validated);

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
            'description' => 'required|string'
        ]);

        $service->update($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service mis à jour avec succès');
    }

    public function adminRequests()
    {
        $requests = DemandeService::with('service', 'user')->latest()->get();
        return view('admin.services.requests', compact('requests'));
    }

    public function updateRequestStatus(Request $request, DemandeService $serviceRequest)
    {
        $validated = $request->validate([
            'statut' => ['required', 'in:' . implode(',', [
                DemandeService::STATUT_EN_ATTENTE,
                DemandeService::STATUT_EN_COURS,
                DemandeService::STATUT_ACCEPTE,
                DemandeService::STATUT_REFUSE
            ])]
        ]);

        $serviceRequest->update([
            'statut' => $validated['statut']
        ]);

        return redirect()->back()
            ->with('success', 'Le statut de la demande a été mis à jour avec succès.');
    }

    public function adminDestroy(Service $service)
    {
        try {
            $service->delete();
            return redirect()->route('admin.services.index')
                ->with('success', 'Le service a été supprimé avec succès');
        } catch (\Exception $e) {
            return redirect()->route('admin.services.index')
                ->with('error', 'Impossible de supprimer ce service. Il est peut-être utilisé par des demandes existantes.');
        }
    }
}
