<?php

namespace Database\Seeders;

use App\Models\RoomType;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(BasicSetupSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(CitySeeder::class);
        $this->call(HotelSeeder::class);
        $this->call(CityHotelSeeder::class);
        $this->call(RoomSeeder::class);
        $this->call(UserSeeder::class);
    }
}
