<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create()->each(function ($user) {
            $reservations = $user->reservations()->saveMany(Reservation::factory(mt_rand(1, 3))->make());
            foreach ($reservations as $reservation) {
                $room_id = [];
                for ($i = 1; $i <= mt_rand(1, 3); $i++) {
                    array_push($room_id, mt_rand(1, Room::all()->count()));
                }
                $reservation->rooms()->attach($room_id, ['status' => mt_rand(0, 1)]);
            }
        });
    }
}
