<?php

namespace Database\Seeders;

use App\Models\CityHotel;
use App\Models\Hotel;
use Illuminate\Database\Seeder;

class CityHotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i < 10; $i++) {
            CityHotel::insert(
                [
                    'city_id' => $i,
                    'hotel_id' => mt_rand(1, Hotel::all()->count()),
                ]
            );
        }
    }
}
