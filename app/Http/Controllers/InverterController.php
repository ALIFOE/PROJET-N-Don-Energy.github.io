<?php

namespace App\Http\Controllers;

use App\Services\Inverters\InverterManager;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\InverterHistory;
use App\Models\InverterSchedule;
use Carbon\Carbon;

class InverterController extends Controller
{
    private $inverterManager;

    public function __construct(InverterManager $inverterManager)
    {
        $this->inverterManager = $inverterManager;
    }

    public function getStatus(Request $request, string $inverterName = null): JsonResponse
    {
        try {
            $inverter = $this->inverterManager->connect($inverterName ?? $this->inverterManager->getDefaultConnection());
            
            return response()->json([
                'status' => 'success',
                'data' => [
                    'currentPower' => $inverter->getCurrentPower(),
                    'dailyEnergy' => $inverter->getDailyEnergy(),
                    'totalEnergy' => $inverter->getTotalEnergy(),
                    'status' => $inverter->getStatus(),
                    'alarms' => $inverter->getAlarms(),
                    'deviceInfo' => $inverter->getDeviceInfo()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getSupportedInverters(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'default' => $this->inverterManager->getDefaultConnection(),
                'supported' => $this->inverterManager->supportedInverters()
            ]
        ]);
    }

    public function getAlarms(Request $request, string $inverterName = null): JsonResponse
    {
        try {
            $inverter = $this->inverterManager->connect($inverterName ?? $this->inverterManager->getDefaultConnection());
            
            return response()->json([
                'status' => 'success',
                'data' => $inverter->getAlarms()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getDeviceInfo(Request $request, string $inverterName = null): JsonResponse
    {
        try {
            $inverter = $this->inverterManager->connect($inverterName ?? $this->inverterManager->getDefaultConnection());
            
            return response()->json([
                'status' => 'success',
                'data' => $inverter->getDeviceInfo()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getHistory(Request $request, string $inverterName, string $period): JsonResponse
    {
        try {
            $inverter = $this->inverterManager->connect($inverterName);
            $now = Carbon::now();
            
            $startDate = match($period) {
                'daily' => $now->startOfDay(),
                'weekly' => $now->startOfWeek(),
                'monthly' => $now->startOfMonth(),
                'yearly' => $now->startOfYear(),
            };

            $history = InverterHistory::where('inverter_name', $inverterName)
                ->where('timestamp', '>=', $startDate)
                ->orderBy('timestamp', 'asc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'period' => $period,
                    'start_date' => $startDate->toISOString(),
                    'end_date' => $now->toISOString(),
                    'measurements' => $history
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getEfficiency(Request $request, string $inverterName): JsonResponse
    {
        try {
            $inverter = $this->inverterManager->connect($inverterName);
            
            $efficiency = [
                'current' => $inverter->getCurrentEfficiency(),
                'daily_average' => $inverter->getDailyAverageEfficiency(),
                'monthly_average' => $inverter->getMonthlyAverageEfficiency(),
                'factors' => $inverter->getEfficiencyFactors()
            ];

            return response()->json([
                'status' => 'success',
                'data' => $efficiency
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateConfiguration(Request $request, string $inverterName): JsonResponse
    {
        try {
            $inverter = $this->inverterManager->connect($inverterName);
            
            $validated = $request->validate([
                'settings' => 'required|array',
                'settings.*' => 'required|string'
            ]);

            $result = $inverter->updateConfiguration($validated['settings']);

            return response()->json([
                'status' => 'success',
                'message' => 'Configuration mise à jour avec succès',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateFirmware(Request $request, string $inverterName): JsonResponse
    {
        try {
            $inverter = $this->inverterManager->connect($inverterName);
            
            $validated = $request->validate([
                'firmware_file' => 'required|file',
                'version' => 'required|string'
            ]);

            $result = $inverter->updateFirmware(
                $validated['firmware_file']->getRealPath(),
                $validated['version']
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Mise à jour du firmware initiée',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function controlProduction(Request $request, string $inverterName): JsonResponse
    {
        try {
            $inverter = $this->inverterManager->connect($inverterName);
            
            $validated = $request->validate([
                'action' => 'required|string|in:start,stop,restart',
                'power_limit' => 'nullable|numeric|min:0|max:100'
            ]);

            $result = $inverter->controlProduction(
                $validated['action'],
                $validated['power_limit'] ?? null
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Commande envoyée avec succès',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getSchedule(Request $request, string $inverterName): JsonResponse
    {
        try {
            $schedule = InverterSchedule::where('inverter_name', $inverterName)
                ->orderBy('start_time')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $schedule
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateSchedule(Request $request, string $inverterName): JsonResponse
    {
        try {
            $validated = $request->validate([
                'schedules' => 'required|array',
                'schedules.*.start_time' => 'required|date_format:H:i',
                'schedules.*.end_time' => 'required|date_format:H:i',
                'schedules.*.power_limit' => 'required|numeric|min:0|max:100',
                'schedules.*.days' => 'required|array',
                'schedules.*.days.*' => 'required|integer|min:0|max:6'
            ]);

            InverterSchedule::where('inverter_name', $inverterName)->delete();
            
            foreach ($validated['schedules'] as $schedule) {
                InverterSchedule::create([
                    'inverter_name' => $inverterName,
                    'start_time' => $schedule['start_time'],
                    'end_time' => $schedule['end_time'],
                    'power_limit' => $schedule['power_limit'],
                    'days' => $schedule['days']
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Planning mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getDiagnostics(Request $request, string $inverterName): JsonResponse
    {
        try {
            $inverter = $this->inverterManager->connect($inverterName);
            
            $diagnostics = $inverter->runDiagnostics();

            return response()->json([
                'status' => 'success',
                'data' => $diagnostics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function resetDevice(Request $request, string $inverterName): JsonResponse
    {
        try {
            $validated = $request->validate([
                'type' => 'required|string|in:soft,hard,factory'
            ]);

            $inverter = $this->inverterManager->connect($inverterName);
            $result = $inverter->reset($validated['type']);

            return response()->json([
                'status' => 'success',
                'message' => 'Réinitialisation effectuée avec succès',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getMaintenanceInfo(Request $request, string $inverterName): JsonResponse
    {
        try {
            $inverter = $this->inverterManager->connect($inverterName);
            
            $maintenanceInfo = [
                'last_maintenance' => $inverter->getLastMaintenanceDate(),
                'next_maintenance' => $inverter->getNextMaintenanceDate(),
                'maintenance_history' => $inverter->getMaintenanceHistory(),
                'recommended_actions' => $inverter->getRecommendedMaintenanceActions()
            ];

            return response()->json([
                'status' => 'success',
                'data' => $maintenanceInfo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
