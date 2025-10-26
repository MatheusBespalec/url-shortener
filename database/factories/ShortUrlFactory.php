<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShortUrl>
 */
class ShortUrlFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->randomNumber(6, true),
            'original_url' => $this->faker->url(),
            'clicks' => 0,
        ];
    }

    public function withClicks(?int $clicks = null): self
    {
        if ($clicks === null) {
            $clicks = $this->faker->numberBetween(0, 100);
        }
        return $this->state(fn (array $attributes) => [
            'clicks' => $clicks,
        ]);
    }
}
