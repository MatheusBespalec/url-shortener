<?php

namespace Feature\Controllers\Apis;

use App\Models\ShortUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ShortUrlControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirect_should_redirect_the_original_url_when_code_found()
    {
        $code = 'abc123';
        $originalUrl = 'https://google.com';
        ShortUrl::create([
            'code' => $code,
            'original_url' => $originalUrl,
        ]);

        $this->get('/s/' . $code)
            ->assertStatus(301)
            ->assertRedirect($originalUrl);
    }

    public function test_redirect_should_return_404_when_has_no_short_url_with_code()
    {
        $code = 'abc123';

        $this->get('/s/' . $code)
            ->assertNotFound();
    }

    public function test_store_should_create_short_link_and_return_resource_when_original_url_is_valid()
    {
        $originalUrl = 'http://google.com';
        $response = $this->postJson('/api/urls', [
            'original_url' => $originalUrl,
        ]);
        $response->assertCreated();
        $response->assertJsonStructure([
            'original_url',
            'short_url',
            'clicks',
        ]);
        $this->assertDatabaseHas('short_urls', [
            'original_url' => $originalUrl,
        ]);
        $this->assertSame($originalUrl, $response->json('original_url'));
        $this->assertSame(0, $response->json('clicks'));
    }

    public function test_store_should_return_422_when_already_exists_short_url_with_provided_original_url()
    {
        $originalUrl = 'http://google.com';
        $this->postJson('/api/urls', [
            'original_url' => $originalUrl,
        ])->assertCreated();
        $response = $this->postJson('/api/urls', [
            'original_url' => $originalUrl,
        ]);
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('original_url');
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
    public function test_store_should_return_422_when_when_original_url_is_invalid(string $originalUrl)
    {
        $response = $this->postJson('/api/urls', [
            'original_url' => $originalUrl,
        ]);
        $response->assertUnprocessable();
        $response->assertJsonValidationErrorFor('original_url');
        $this->assertDatabaseMissing('short_urls', [
            'original_url' => $originalUrl,
        ]);
    }

    public function test_index_should_list_short_urls()
    {
        ShortUrl::factory()->count(10)->create();
        $response = $this->get('/api/urls');
        $response->assertOk();
        $this->assertEquals(10, $response->json('meta.total'));
    }

    public function test_index_should_filter_by_original_url_when_filter_is_present()
    {
        ShortUrl::factory()->count(10)->create();
        $originalUrl = 'http://google.com';
        ShortUrl::factory()->create([
            'original_url' => $originalUrl,
        ]);
        $response = $this->get('/api/urls?original_url=' . urlencode($originalUrl));
        $response->assertOk();
        $this->assertEquals(1, $response->json('meta.total'));
        $this->assertEquals($originalUrl, $response->json('data.0.original_url'));
    }

    public function test_index_should_paginate_results_when_pagination_params_is_present()
    {
        ShortUrl::factory()->count(9)->create();
        $response = $this->get('/api/urls?per_page=5&page=2');
        $response->assertOk();
        $this->assertEquals(9, $response->json('meta.total'));
        $this->assertCount(4, $response->json('data'));
    }
}
