<?php

namespace Feature\UseCases\ShortUrls;

use App\Exceptions\RedirectTargetNotFoundException;
use App\Models\ShortUrl;
use App\UseCases\ShortUrls\GetOriginalUrlUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetOriginalUrlUseCaseTest extends TestCase
{
    use RefreshDatabase;

    private GetOriginalUrlUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->useCase = new GetOriginalUrlUseCase();
    }

    public function test_should_return_original_url_when_short_url_exists()
    {
        $code = 'abc123';
        $originalUrl = 'https://google.com';

        ShortUrl::create([
            'code' => $code,
            'original_url' => $originalUrl,
        ]);

        $output = $this->useCase->execute($code);

        $this->assertSame($originalUrl, $output);
    }

    public function test_should_throws_RedirectTargetNotFoundException_when_short_url_does_not_exists()
    {
        $this->expectException(RedirectTargetNotFoundException::class);

        $code = 'abc123';
        $this->useCase->execute($code);
    }
}
