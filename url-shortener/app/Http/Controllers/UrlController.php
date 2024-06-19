<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Url;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class UrlController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function shorten(Request $request)
    {
        try {
            $validated = $request->validate([
                'original_url' => 'required|url',
                'prefix' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9]+$/'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $originalUrl = $request->input('original_url');
        $prefix = '';

        $urlComponents = parse_url($originalUrl);
        if (isset($urlComponents['path'])) {
            $path = ltrim($urlComponents['path'], '/');
            $segments = explode('/', $path);
            if (!empty($segments[0])) {
                $prefix = $segments[0];
            }
        }

        if (!$this->isUrlSafe($originalUrl)) {
            return response()->json(['error' => 'URL is not safe'], 400);
        }

        $url = Url::where('original_url', $originalUrl)->where('prefix', $prefix)->first();

        if (!$url) {
            $shortHash = $this->generateUniqueHash();
            $url = Url::create([
                'original_url' => $originalUrl,
                'short_hash' => $shortHash,
                'prefix' => $prefix
            ]);
        }

        $shortUrl = $prefix ? url("/{$prefix}/{$url->short_hash}") : url("/{$url->short_hash}");

        return response()->json(['short_url' => $shortUrl]);
    }

    public function redirect($prefix, $hash = null)
    {
        if ($hash === null) {
            $hash = $prefix;
            $prefix = '';
        }

        $urlQuery = Url::where('short_hash', $hash);
        if (!empty($prefix)) {
            $urlQuery->where('prefix', $prefix);
        }

        $url = $urlQuery->firstOrFail();
        return redirect()->away($url->original_url);
    }

    private function generateUniqueHash()
    {
        do {
            $hash = Str::random(6);
        } while (Url::where('short_hash', $hash)->exists());

        return $hash;
    }

    private function isUrlSafe($url)
    {
        $apiKey = env('GOOGLE_SAFE_BROWSING_API_KEY');
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
