<?php

namespace Database\Factories;

use App\Enums\VideoStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "fk_user_id" => User::factory(),
            "title" => fake()->word(),
            "path" => fake()->uuid(),
            "status" => VideoStatus::Active,
        ];
    }
    
}
