<?php

namespace App\UseCases\ShortUrls;

use App\Models\ShortUrl;
use App\UrlEncoders\CodeGenerator;
use App\UseCases\ShortUrls\DTO\CreateShortUrlDTO;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Log;

class CreateShortUrlUseCase
{
    public function __construct(private CodeGenerator $urlEncoder) { }

    public function execute(CreateShortUrlDTO $data): ShortUrl
    {
        $data->validate($data->toArray());

        $retries = 0;
        do {
            try {
                return ShortUrl::create([
                    'original_url' => $data->originalUrl,
                    'code' => $this->urlEncoder->encode(),
                    'clicks' => 0
                ]);
            } catch (UniqueConstraintViolationException) {
                $retries++;
            }
        } while ($retries < 5);
        Log::error('Failed to create short url. Max of 5 retries exceeded.');
        throw new \Exception('Failed to create short url. Max of 5 retries exceeded.');
    }
}
