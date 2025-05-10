<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class QuotaMonitoringService
{
    // Clés pour le cache
    protected const QUOTA_USAGE_KEY = 'openai_quota_usage';
    protected const QUOTA_RESET_KEY = 'openai_quota_reset';
    protected const ERROR_COUNT_KEY = 'openai_error_count';
    
    // Seuils d'alerte (à ajuster selon vos besoins)
    protected const WARNING_THRESHOLD = 80; // 80% du quota
    protected const ERROR_THRESHOLD = 3;    // Nombre d'erreurs avant le mode dégradé

    public function recordUsage()
    {
        $currentUsage = Cache::get(self::QUOTA_USAGE_KEY, 0);
        Cache::put(self::QUOTA_USAGE_KEY, $currentUsage + 1, now()->addHours(24));
        
        $this->checkWarningThreshold($currentUsage + 1);
    }

    public function recordError()
    {
        $errorCount = Cache::get(self::ERROR_COUNT_KEY, 0);
        Cache::put(self::ERROR_COUNT_KEY, $errorCount + 1, now()->addMinutes(15));
        
        if ($errorCount + 1 >= self::ERROR_THRESHOLD) {
            $this->enableFallbackMode();
        }
    }

    public function resetErrorCount()
    {
        Cache::forget(self::ERROR_COUNT_KEY);
    }

    public function isInFallbackMode(): bool
    {
        return Cache::get('openai_fallback_mode', false);
    }

    protected function enableFallbackMode()
    {
        Cache::put('openai_fallback_mode', true, now()->addMinutes(30));
        Log::warning('QuotaMonitoring: Mode dégradé activé en raison de trop nombreuses erreurs');
    }

    protected function disableFallbackMode()
    {
        Cache::forget('openai_fallback_mode');
        $this->resetErrorCount();
    }

    protected function checkWarningThreshold($currentUsage)
    {
        if ($currentUsage && $currentUsage % 100 === 0) {
            Log::info('QuotaMonitoring: Utilisation actuelle', [
                'usage' => $currentUsage,
                'date' => now()->format('Y-m-d H:i:s')
            ]);
        }

        // Si on dépasse le seuil d'avertissement
        if ($this->calculateUsagePercentage($currentUsage) >= self::WARNING_THRESHOLD) {
            Log::warning('QuotaMonitoring: Seuil d\'utilisation élevé', [
                'usage' => $currentUsage,
                'threshold' => self::WARNING_THRESHOLD . '%'
            ]);
        }
    }

    protected function calculateUsagePercentage($currentUsage): float
    {
        // À adapter selon votre limite quotidienne
        $dailyLimit = 1000;
        return ($currentUsage / $dailyLimit) * 100;
    }

    public function getQuotaStatus(): array
    {
        $currentUsage = Cache::get(self::QUOTA_USAGE_KEY, 0);
        $errorCount = Cache::get(self::ERROR_COUNT_KEY, 0);
        $inFallbackMode = $this->isInFallbackMode();

        return [
            'usage' => $currentUsage,
            'usage_percentage' => $this->calculateUsagePercentage($currentUsage),
            'error_count' => $errorCount,
            'fallback_mode' => $inFallbackMode,
            'status' => $this->getStatusMessage($currentUsage, $errorCount, $inFallbackMode)
        ];
    }

    protected function getStatusMessage($usage, $errorCount, $fallbackMode): string
    {
        if ($fallbackMode) {
            return 'Service en mode dégradé - Utilisation restreinte';
        }

        if ($errorCount > 0) {
            return 'Service instable - Surveillance accrue';
        }

        $percentage = $this->calculateUsagePercentage($usage);
        if ($percentage >= self::WARNING_THRESHOLD) {
            return 'Quota élevé - Utilisation limitée recommandée';
        }

        return 'Service opérationnel';
    }
}
