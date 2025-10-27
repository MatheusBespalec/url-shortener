<?php

namespace Feature\Listeners;

use App\Events\ShortUrlAccessed;
use App\Listeners\IncrementShortUrlClicks;
use App\Models\ShortUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(IncrementShortUrlClicks::class)]
class IncrementShortUrlClicksTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_increment_short_url_clicks()
    {
        $shortUrl = ShortUrl::factory()->create(['clicks' => 0]);
        $listener = new IncrementShortUrlClicks();
        $listener->handle(new ShortUrlAccessed($shortUrl));

        $shortUrl->refresh();
        $this->assertSame(1, $shortUrl->clicks);
    }
}
