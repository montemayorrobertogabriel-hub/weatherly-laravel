<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function search(Request $request)
    {
        $city = $request->query('city');

        if (!$city) {
            return response()->json(['cod' => 400, 'message' => 'City is required']);
        }

        $apiKey = env('OPENWEATHER_API_KEY');

        // Fetch CURRENT weather
        $currentResponse = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'q' => $city,
            'appid' => $apiKey,
            'units' => 'metric'
        ]);

        if ($currentResponse->failed()) {
            return response()->json(['cod' => 404, 'message' => 'City not found']);
        }

        $currentData = $currentResponse->json();

        // Fetch 5-day / 3-hour forecast (FREE endpoint)
        $forecastResponse = Http::get('https://api.openweathermap.org/data/2.5/forecast', [
            'q' => $city,
            'appid' => $apiKey,
            'units' => 'metric'
        ]);

        $forecastData = $forecastResponse->json();

        // Group forecast into 5 daily entries
        $daily = [];
        foreach ($forecastData['list'] as $entry) {
            $date = substr($entry['dt_txt'], 0, 10);

            if (!isset($daily[$date])) {
                $daily[$date] = [
                    'dt' => $entry['dt'],
                    'temp' => [],
                    'weather' => $entry['weather'][0]['main']
                ];
            }

            $daily[$date]['temp'][] = $entry['main']['temp'];
        }

        // Format top 5 days
        $formatted = [];
        foreach ($daily as $date => $info) {
            $formatted[] = [
                'dt' => $info['dt'],
                'temp' => [
                    'day' => round(array_sum($info['temp']) / count($info['temp']))
                ],
                'weather' => [
                    [ 'main' => $info['weather'] ]
                ]
            ];
        }

        $formatted = array_slice($formatted, 0, 5);

        // Attach to current weather
        $currentData['daily'] = $formatted;

        return response()->json($currentData);
    }
}
