<?php
namespace App\Services;

use App\Models\Url;
use Illuminate\Support\Str;

class UrlShorteningService
{
    public function generateUniqueHash(): string
    {
        do {
            $hash = Str::random(6);
        } while (Url::where('short_hash', $hash)->exists());

        return $hash;
    }

    public function shortenUrl(string $originalUrl, string $prefix = ''): Url
    {
        $url = Url::where('original_url', $originalUrl)->where('prefix', $prefix)->first();

        if (!$url) {
            $shortHash = $this->generateUniqueHash();
            $url = Url::create([
                'original_url' => $originalUrl,
                'short_hash' => $shortHash,
                'prefix' => $prefix
            ]);
        }

        return $url;
    }
}
