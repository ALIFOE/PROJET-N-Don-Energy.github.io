<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.openweathermap.org/data/2.5';

    public function __construct()
    {
        $this->apiKey = config('services.openweathermap.key');
    }

    public function getForecast(string $location)
    {
        try {
            if (!$this->apiKey) {
                throw new \Exception('Clé API OpenWeatherMap non configurée');
            }

            $response = Http::timeout(10)->get("{$this->baseUrl}/forecast", [
                'q' => $location,
                'appid' => $this->apiKey,
                'units' => 'metric',
                'lang' => 'fr'
            ]);

            if ($response->failed()) {
                throw new \Exception('Erreur lors de la récupération des données météo');
            }

            $data = $response->json();

            if (isset($data['cod']) && $data['cod'] === '404') {
                throw new \Exception('Localisation non trouvée');
            }

            return $this->formatForecastData($data);

        } catch (\Exception $e) {
            Log::error('WeatherService error: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function formatForecastData(array $data): array
    {
        if (!isset($data['list']) || empty($data['list'])) {
            throw new \Exception('Données météo invalides');
        }

        $forecasts = collect($data['list'])->map(function ($item) {
            return [
                'date' => $item['dt_txt'],
                'temperature' => $item['main']['temp'],
                'humidite' => $item['main']['humidity'],
                'nuages' => $item['clouds']['all'],
                'vent' => [
                    'vitesse' => $item['wind']['speed'],
                    'direction' => $item['wind']['deg']
                ]
            ];
        })->take(8); // Prendre les prochaines 24 heures (8 périodes de 3 heures)

        return [
            'ville' => $data['city']['name'],
            'pays' => $data['city']['country'],
            'previsions' => $forecasts->values()->all()
        ];
    }
}
