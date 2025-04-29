<?php

namespace App\Http\Controllers;

use App\Models\Installation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InstallationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $installations = Installation::with('utilisateur')->get();
        return response()->json($installations);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'utilisateur_id' => 'required|exists:utilisateurs,id',
            'nom' => 'required|string|max:255',
            'date_installation' => 'required|date',
            'puissance_totale' => 'required|numeric|min:0',
            'surface_totale' => 'nullable|numeric|min:0',
            'nombre_panneaux' => 'required|integer|min:1',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'orientation' => 'nullable|string|max:50',
            'inclinaison' => 'nullable|numeric|between:0,90',
            'description' => 'nullable|string',
            'statut' => 'required|in:active,en maintenance,inactive',
            'cout_installation' => 'nullable|numeric|min:0',
            'date_mise_service' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $installation = Installation::create($request->all());
        return response()->json($installation, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Installation $installation)
    {
        $installation->load('utilisateur', 'panneaux', 'onduleurs', 'systemesStockage');
        return response()->json($installation);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Installation $installation)
    {
        $validator = Validator::make($request->all(), [
            'utilisateur_id' => 'sometimes|required|exists:utilisateurs,id',
            'nom' => 'sometimes|required|string|max:255',
            'date_installation' => 'sometimes|required|date',
            'puissance_totale' => 'sometimes|required|numeric|min:0',
            'surface_totale' => 'nullable|numeric|min:0',
            'nombre_panneaux' => 'sometimes|required|integer|min:1',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'orientation' => 'nullable|string|max:50',
            'inclinaison' => 'nullable|numeric|between:0,90',
            'description' => 'nullable|string',
            'statut' => 'sometimes|required|in:active,en maintenance,inactive',
            'cout_installation' => 'nullable|numeric|min:0',
            'date_mise_service' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $installation->update($request->all());
        return response()->json($installation);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Installation $installation)
    {
        $installation->delete();
        return response()->json(null, 204);
    }

    public function getProductionData(Installation $installation)
    {
        $productionData = $installation->donneesProduction()
            ->orderBy('date_heure', 'desc')
            ->take(100)
            ->get();
        return response()->json($productionData);
    }

    public function getConsommationData(Installation $installation)
    {
        $consommationData = $installation->donneesConsommation()
            ->orderBy('date_heure', 'desc')
            ->take(100)
            ->get();
        return response()->json($consommationData);
    }
}
