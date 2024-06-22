<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShortenUrlRequest;
use App\Services\UrlValidationService;
use App\Services\UrlSafetyService;
use App\Services\UrlShorteningService;
use App\Models\Url;

class UrlController extends Controller
{
    private $urlValidationService;
    private $urlSafetyService;
    private $urlShorteningService;

    public function __construct(
        UrlValidationService $urlValidationService,
        UrlSafetyService $urlSafetyService,
        UrlShorteningService $urlShorteningService
    ) {
        $this->urlValidationService = $urlValidationService;
        $this->urlSafetyService = $urlSafetyService;
        $this->urlShorteningService = $urlShorteningService;
    }

    public function index()
    {
        return view('welcome');
    }

    public function shorten(ShortenUrlRequest $request)
    {
        $originalUrl = $request->input('original_url');
        $prefix = $this->extractPrefixFromUrl($originalUrl);

        if (!$this->urlSafetyService->isUrlSafe($originalUrl)) {
            return response()->json(['error' => 'URL is not safe'], 400);
        }

        $url = $this->urlShorteningService->shortenUrl($originalUrl, $prefix);

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

    private function extractPrefixFromUrl($url): string
    {
        $urlComponents = parse_url($url);
        if (isset($urlComponents['path'])) {
            $path = ltrim($urlComponents['path'], '/');
            $segments = explode('/', $path);
            return !empty($segments[0]) ? $segments[0] : '';
        }
        return '';
    }
}
