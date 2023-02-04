<?php

namespace Database\Factories;

use App\Models\Hotel;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => rtrim(ucfirst($this->faker->text('10')), '.'),
            'description' => $this->faker->sentence(),
            'room_type_id' => RoomType::factory()->create(),
            'hotel_id' => mt_rand(1, Hotel::all()->count()),
        ];
    }
}
