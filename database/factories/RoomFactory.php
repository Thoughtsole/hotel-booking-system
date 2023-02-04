<?php

namespace Database\Factories;

use App\Models\Room;
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
            'name' => $this->faker->word('15'),
            'description' => $this->faker->word(30),
            'room_type_id' => mt_rand(1, RoomType::all()->count()),
            'room_id' => mt_rand(1, Room::all()->count()),
        ];
    }
}
