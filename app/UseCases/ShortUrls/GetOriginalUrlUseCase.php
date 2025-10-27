<?php

namespace App\UseCases\ShortUrls;

use App\Events\ShortUrlAccessed;
use App\Exceptions\RedirectTargetNotFoundException;
use App\Models\ShortUrl;

class GetOriginalUrlUseCase
{
    /**
     * @param string $code
     * @return string
     * @throws RedirectTargetNotFoundException
     */
    public function execute(string $code): string
    {
        $shortUrl = ShortUrl::where('code', $code)->first();
        if ($shortUrl === null) {
            throw new RedirectTargetNotFoundException('The short URL code provided does not exist.');
        }
        ShortUrlAccessed::dispatch($shortUrl);
        return $shortUrl->original_url;
    }
}
