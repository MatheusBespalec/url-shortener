<?php

namespace Tests\Controllers\Apis;

use App\Models\ShortUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
