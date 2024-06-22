<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    protected $model = Url::class;

    public function definition()
    {
        return [
            'original_url' => $this->faker->url,
            'short_hash' => Str::random(6),
            'prefix' => $this->faker->word,
        ];
    }
}
