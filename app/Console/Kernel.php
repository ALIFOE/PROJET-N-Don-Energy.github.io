<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Vérification des conditions météo toutes les 15 minutes
        $schedule->command('meteo:verifier-conditions')
            ->everyFifteenMinutes()
            ->withoutOverlapping();

        // Collecte des données des onduleurs toutes les 5 minutes
        $schedule->command('inverters:collect-data')
            ->everyFiveMinutes()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/inverter-data.log'));
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
