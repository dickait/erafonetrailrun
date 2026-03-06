<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Country;
use App\Models\Event;
use App\Models\Province;
use App\Models\City;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@erafonetrailrun.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Country
        $indonesia = Country::create(['name' => 'Indonesia', 'code' => 'IDN']);
        // Country::create(['name' => 'Malaysia', 'code' => 'MYS']);
        // Country::create(['name' => 'Singapore', 'code' => 'SGP']);



        // Create Event
        $event = Event::create([
            'name' => 'ERA TRAIL RUN 2026',
            'slug' => 'era-trail-run-2026',
            'description' => 'Experience the ultimate trail running adventure through the stunning landscapes of Indonesia. Join hundreds of runners in this epic journey through dense tropical forests, volcanic terrains, and breathtaking mountain vistas. Whether you\'re a beginner or an experienced trail runner, ERA TRAIL RUN 2026 has a category for you.',
            'location' => 'Bogor Nirwana Residence, Bogor',
            'event_date' => '2026-06-21 06:00:00',
            'registration_open' => '2026-01-01 00:00:00',
            'registration_close' => '2026-06-20 23:59:59',
            'is_active' => true,
        ]);

        // Create Categories
        Category::create([
            'event_id' => $event->id,
            'name' => '5K Family Trail',
            'slug' => '5k-family-trail',
            'description' => '5K Family Trail',
            'price' => 250000,
            'early_bird_price' => 200000,
            'early_bird_deadline' => '2026-03-31 23:59:59',
            'quota' => 500,
            'distance_km' => 6.53,
            'elevation' => 294,
            'cot' => 1,
            'effort_km' => 6.5,
            'color' => '#22c55e',
        ]);

        Category::create([
            'event_id' => $event->id,
            'name' => '10K',
            'slug' => '10k',
            'description' => '10K',
            'price' => 350000,
            'early_bird_price' => 300000,
            'early_bird_deadline' => '2026-03-31 23:59:59',
            'quota' => 300,
            'distance_km' => 11.71,
            'elevation' => 294,
            'cot' => 3,
            'effort_km' => 11.7,
            'color' => '#f59e0b',
        ]);

        Category::create([
            'event_id' => $event->id,
            'name' => '15K',
            'slug' => '15k',
            'description' => '15K',
            'price' => 500000,
            'early_bird_price' => 425000,
            'early_bird_deadline' => '2026-03-31 23:59:59',
            'quota' => 200,
            'distance_km' => 16.71,
            'elevation' => 294,
            'cot' => 5,
            'effort_km' => 16.7,
            'color' => '#ef4444',
        ]);
    }
}
