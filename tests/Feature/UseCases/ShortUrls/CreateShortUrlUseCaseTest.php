<?php

namespace Feature\UseCases\ShortUrls;


use App\UrlEncoders\CodeGenerator;
use App\UseCases\ShortUrls\CreateShortUrlUseCase;
use App\UseCases\ShortUrls\DTO\CreateShortUrlDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

#[CoversClass(CreateShortUrlUseCase::class)]
class CreateShortUrlUseCaseTest extends TestCase
{
    use RefreshDatabase;

    private CreateShortUrlUseCase $useCase;
    private MockObject&CodeGenerator $urlEncoder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->urlEncoder = $this->createMock(CodeGenerator::class);
        $this->useCase = new CreateShortUrlUseCase($this->urlEncoder);
    }

    public function test_should_create_short_url_when_original_url_is_a_valid_link()
    {
        $code = 'abc123';
        $originalUrl = 'https://google.com';
        $this->urlEncoder->expects($this->once())->method('encode')->willReturn($code);

        $shortUrl = $this->useCase->execute(new CreateShortUrlDTO($originalUrl));

        $this->assertDatabaseHas('short_urls', [
            'original_url' => $originalUrl,
            'code' => $code,
            'clicks' => 0,
        ]);
        $this->assertSame($code, $shortUrl->code);
        $this->assertSame($originalUrl, $shortUrl->original_url);
        $this->assertSame(0, $shortUrl->clicks);
    }

    public function test_should_throw_ValidationException_when_already_exists_short_url_with_provided_original_url()
    {
        $this->expectException(ValidationException::class);
        $code = 'abc123';
        $originalUrl = 'https://google.com';
        $this->urlEncoder->expects($this->once())->method('encode')->willReturn($code);

        $this->useCase->execute(new CreateShortUrlDTO($originalUrl));
        $this->useCase->execute(new CreateShortUrlDTO($originalUrl));
    }

    public function test_should_regenerate_code_when_is_duplicated_on_database()
    {
        $code1 = 'abc123';
        $code2 = 'abc1234';
        $originalUrl1 = 'https://google.com';
        $originalUrl2 = 'https://test.com';
        $this->urlEncoder->expects($this->exactly(3))->method('encode')->willReturn($code1, $code1, $code2);

        $this->useCase->execute(new CreateShortUrlDTO($originalUrl1));
        $this->useCase->execute(new CreateShortUrlDTO($originalUrl2));

        $this->assertDatabaseHas('short_urls', [
            'original_url' => $originalUrl1,
            'code' => $code1,
        ]);
        $this->assertDatabaseHas('short_urls', [
            'original_url' => $originalUrl2,
            'code' => $code2,
        ]);
    }

    public function test_should_regenerate_code_with_five_retries_and_fail_when_keep_duplicated_on_database()
    {
        $this->expectException(\Exception::class);
        $code1 = 'abc123';
        $originalUrl1 = 'https://google.com';
        $originalUrl2 = 'https://test.com';
        $this->urlEncoder->expects($this->exactly(6))->method('encode')->willReturn($code1);

        $this->useCase->execute(new CreateShortUrlDTO($originalUrl1));
        $this->useCase->execute(new CreateShortUrlDTO($originalUrl2));
    }

    public static function invalidUrlProvider(): array
    {
        return [
            'abc' => ['abc'],
            'file:/test' => ['file:/test'],
            'test.com' => ['test.com'],
            'httd://test.com' => ['httd://test.com'],
            '123' => ['123'],
        ];
    }

    #[DataProvider('invalidUrlProvider')]
    public function test_should_throws_InvalidArgumentException_when_original_url_is_invalid_link(string $originalUrl)
    {
        $this->expectException(ValidationException::class);

        $this->useCase->execute(new CreateShortUrlDTO($originalUrl));
    }
}
