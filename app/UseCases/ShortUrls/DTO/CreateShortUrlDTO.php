<?php

namespace App\UseCases\ShortUrls\DTO;

use Spatie\LaravelData\Attributes\Validation\ActiveUrl;
use Spatie\LaravelData\Data;

class CreateShortUrlDTO extends Data
{
    public function __construct(
        #[ActiveUrl]
        public string $originalUrl,
    ) { }
}
