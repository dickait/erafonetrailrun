<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryPrice;
use App\Models\Promotion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PricingAndPromotionsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data to avoid duplicates if rerun
        Schema::disableForeignKeyConstraints();
        CategoryPrice::query()->delete();
        Promotion::query()->delete();
        Schema::enableForeignKeyConstraints();

        $cat5k = Category::where('slug', '5k-family-fun-trail')->first();
        $cat10k = Category::where('slug', '10k')->first();
        $cat15k = Category::where('slug', '15k')->first();

        if ($cat5k) {
            CategoryPrice::create(['category_id' => $cat5k->id, 'pax' => 2, 'price' => 550000]);
            CategoryPrice::create(['category_id' => $cat5k->id, 'pax' => 3, 'price' => 700000]);
        }

        if ($cat10k) {
            CategoryPrice::create(['category_id' => $cat10k->id, 'pax' => 1, 'price' => 350000]);
        }

        if ($cat15k) {
            CategoryPrice::create(['category_id' => $cat15k->id, 'pax' => 1, 'price' => 400000]);
        }

        // Promotions & Discount Codes (Unified in Promotion table)
        Promotion::create([
            'name' => 'Early Bird',
            'code' => 'EARLYBIRD50',
            'type' => 'earlybird',
            'discount_type' => 'fixed',
            'discount_value' => 50000,
            'start_date' => now(),
            'end_date' => now()->addDays(3),
            'quota' => 1000,
        ]);

        Promotion::create([
            'name' => 'Komunitas',
            'code' => 'KOMUNITAS50',
            'type' => 'discount_code',
            'discount_type' => 'fixed',
            'discount_value' => 50000,
            'quota' => 500,
        ]);

        Promotion::create([
            'name' => 'Staff Erafone',
            'code' => 'STAFF100',
            'type' => 'discount_code',
            'discount_type' => 'percent',
            'discount_value' => 100,
            'quota' => 100,
        ]);

        Promotion::create([
            'name' => 'Influencer',
            'code' => 'INFLUENCER100',
            'type' => 'discount_code',
            'discount_type' => 'percent',
            'discount_value' => 100,
            'quota' => 50,
        ]);
    }
}
