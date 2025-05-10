<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OpenAIService;
use App\Services\QuotaMonitoringService;
use Illuminate\Support\Facades\Cache;

class ServiceStatusController extends Controller
{
    protected $openAIService;
    protected $quotaMonitoring;

    public function __construct(OpenAIService $openAIService, QuotaMonitoringService $quotaMonitoring)
    {
        $this->openAIService = $openAIService;
        $this->quotaMonitoring = $quotaMonitoring;
    }

    public function index()
    {
        $status = $this->quotaMonitoring->getQuotaStatus();
        $recentErrors = Cache::get('openai_recent_errors', []);
        
        return view('admin.services.status', [
            'quotaStatus' => $status,
            'recentErrors' => $recentErrors,
            'lastCheck' => now()->format('Y-m-d H:i:s')
        ]);
    }

    public function resetFallbackMode()
    {
        Cache::forget('openai_fallback_mode');
        Cache::forget('openai_error_count');
        
        return redirect()->route('admin.services.status')
            ->with('success', 'Le mode dégradé a été désactivé');
    }

    public function resetQuotaCount()
    {
        Cache::forget('openai_quota_usage');
        
        return redirect()->route('admin.services.status')
            ->with('success', 'Le compteur de quota a été réinitialisé');
    }
}
