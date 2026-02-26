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
            'name' => 'Erafone Trail Run 2026',
            'slug' => 'erafone-trail-run-2026',
            'description' => 'Experience the ultimate trail running adventure through the stunning landscapes of Indonesia. Join hundreds of runners in this epic journey through dense tropical forests, volcanic terrains, and breathtaking mountain vistas. Whether you\'re a beginner or an experienced trail runner, Erafone Trail Run 2026 has a category for you.',
            'location' => 'Gunung Pancar, Sentul, Bogor',
            'event_date' => '2026-06-15 06:00:00',
            'registration_open' => '2026-01-01 00:00:00',
            'registration_close' => '2026-06-01 23:59:59',
            'is_active' => true,
        ]);

        // Create Categories
        Category::create([
            'event_id' => $event->id,
            'name' => '5K Fun Run',
            'slug' => '5k-fun-run',
            'description' => 'Perfect for beginners! A scenic 5-kilometer trail through gentle slopes with stunning panoramic views. Enjoy the beauty of nature at your own pace.',
            'price' => 250000,
            'early_bird_price' => 200000,
            'early_bird_deadline' => '2026-03-31 23:59:59',
            'quota' => 500,
            'distance_km' => 5,
            'elevation' => 150,
            'cot' => 3,
            'effort_km' => 6.5,
            'color' => '#22c55e',
        ]);

        Category::create([
            'event_id' => $event->id,
            'name' => '10K Challenge',
            'slug' => '10k-challenge',
            'description' => 'Ready for a challenge? Tackle 10 kilometers of diverse terrain including forest trails, river crossings, and moderate elevation changes.',
            'price' => 350000,
            'early_bird_price' => 300000,
            'early_bird_deadline' => '2026-03-31 23:59:59',
            'quota' => 300,
            'distance_km' => 10,
            'elevation' => 450,
            'cot' => 5,
            'effort_km' => 14.5,
            'color' => '#f59e0b',
        ]);

        Category::create([
            'event_id' => $event->id,
            'name' => '21K Ultra Trail',
            'slug' => '21k-ultra-trail',
            'description' => 'The ultimate endurance test! Conquer 21 kilometers of challenging mountain trails with significant elevation gain. Only for experienced trail runners.',
            'price' => 500000,
            'early_bird_price' => 425000,
            'early_bird_deadline' => '2026-03-31 23:59:59',
            'quota' => 200,
            'distance_km' => 21,
            'elevation' => 1200,
            'cot' => 8,
            'effort_km' => 33.0,
            'color' => '#ef4444',
        ]);
    }
}
