<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class UrlSafetyService
{
    public function isUrlSafe(string $url): bool
    {
        $apiKey = config('services.google_safe_browsing.api_key');
        $response = Http::post("https://safebrowsing.googleapis.com/v4/threatMatches:find?key={$apiKey}", [
            'client' => [
                'clientId' => 'INTUS',
                'clientVersion' => '1.5.2'
            ],
            'threatInfo' => [
                'threatTypes' => ['MALWARE', 'SOCIAL_ENGINEERING'],
                'platformTypes' => ['WINDOWS'],
                'threatEntryTypes' => ['URL'],
                'threatEntries' => [
                    ['url' => $url]
                ]
            ]
        ]);

        return empty($response->json()['matches']);
    }
}

