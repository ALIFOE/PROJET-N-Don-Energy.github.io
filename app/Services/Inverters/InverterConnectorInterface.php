<?php

namespace App\Services\Inverters;

interface InverterConnectorInterface
{
    /**
     * Récupère les données en temps réel de l'onduleur
     *
     * @return array
     */
    public function fetchRealTimeData(): array;

    /**
     * Récupère les données de production journalière
     *
     * @return float
     */
    public function getDailyProduction(): float;

    /**
     * Récupère l'état actuel de l'onduleur
     *
     * @return int
     */
    public function getStatus(): int;
}