<?php

namespace App\UseCases\ShortUrls\DTO;

use Spatie\LaravelData\Attributes\Validation\ActiveUrl;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Attributes\Validation\Url;
use Spatie\LaravelData\Data;

class CreateShortUrlDTO extends Data
{
    public function __construct(
        #[Url(protocols: ['http', 'https'])]
        #[Unique('short_urls')]
        public string $originalUrl,
    ) { }
}
