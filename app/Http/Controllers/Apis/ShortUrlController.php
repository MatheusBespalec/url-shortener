<?php

namespace App\Http\Controllers\Apis;

use App\Exceptions\RedirectTargetNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ShortUrlResource;
use App\Models\ShortUrl;
use App\UseCases\ShortUrls\CreateShortUrlUseCase;
use App\UseCases\ShortUrls\DTO\CreateShortUrlDTO;
use App\UseCases\ShortUrls\GetOriginalUrlUseCase;

class ShortUrlController extends Controller
{
    public function index()
    {
        return ShortUrlResource::collection(ShortUrl::paginate());
    }

    public function store(CreateShortUrlDTO $data, CreateShortUrlUseCase $useCase)
    {
        $shortUrl = $useCase->execute($data);
        return response(new ShortUrlResource($shortUrl), 201);
    }

    public function redirect(string $code, GetOriginalUrlUseCase $useCase)
    {
        try {
            $originalUrl = $useCase->execute($code);
            return redirect($originalUrl, 301);
        } catch (RedirectTargetNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
