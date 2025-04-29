<?php

namespace App\Http\Controllers;

use App\Models\Installation;
use App\Services\InverterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $inverterService;

    public function __construct(InverterService $inverterService)
    {
        $this->inverterService = $inverterService;
    }

    public function index()
    {
        $user = Auth::user();
        $installations = $user->installations()->with('productionData')->get();
        
        $dashboardData = [];
        
        foreach ($installations as $installation) {
            // Récupération des données en temps réel
            $liveData = $this->inverterService->getLiveData($installation);
            
            // Récupération des données historiques pour les graphiques
            $historicalData = $this->inverterService->getHistoricalData(
                $installation,
                Carbon::now()->startOfDay(),
                Carbon::now()
            );
            
            $dashboardData[$installation->id] = [
                'installation' => $installation,
                'live_data' => $liveData,
                'historical_data' => $historicalData,
                'is_connected' => $this->inverterService->checkConnection($installation),
            ];
        }

        return view('dashboard', [
            'dashboardData' => $dashboardData
        ]);
    }

    public function getInstallationData(Installation $installation)
    {
        $this->authorize('view', $installation);
        
        $liveData = $this->inverterService->getLiveData($installation);
        
        return response()->json([
            'success' => true,
            'data' => $liveData
        ]);
    }

    public function getHistoricalData(Request $request, Installation $installation)
    {
        $this->authorize('view', $installation);
        
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        
        $data = $this->inverterService->getHistoricalData($installation, $startDate, $endDate);
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
} 