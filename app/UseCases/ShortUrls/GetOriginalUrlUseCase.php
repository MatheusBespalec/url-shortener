<?php

namespace App\UseCases\ShortUrls;

use App\Exceptions\RedirectTargetNotFoundException;
use App\Models\ShortUrl;

class GetOriginalUrlUseCase
{
    public function execute(string $code): string
    {
        $shortUrl = ShortUrl::where('code', $code)->first();
        if ($shortUrl === null) {
            throw new RedirectTargetNotFoundException('The short URL code provided does not exist.');
        }
        return $shortUrl->original_url;
    }
}
