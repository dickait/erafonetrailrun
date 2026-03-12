<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryPrice;
use App\Models\Promotion;
use App\Models\DiscountCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PricingAndPromotionsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data to avoid duplicates if rerun
        Schema::disableForeignKeyConstraints();
        CategoryPrice::query()->delete();
        DiscountCode::query()->delete();
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

        // Promotions
        $earlyBird = Promotion::create([
            'name' => 'Early Bird',
            'type' => 'earlybird',
            'discount_type' => 'fixed',
            'discount_value' => 50000,
            'start_date' => now(),
            'end_date' => now()->addDays(3),
        ]);

        $community = Promotion::create([
            'name' => 'Komunitas',
            'type' => 'discount_code',
            'discount_type' => 'fixed',
            'discount_value' => 50000,
        ]);

        $staff = Promotion::create([
            'name' => 'Staff Erafone',
            'type' => 'discount_code',
            'discount_type' => 'percent',
            'discount_value' => 100,
        ]);

        $influencer = Promotion::create([
            'name' => 'Influencer',
            'type' => 'discount_code',
            'discount_type' => 'percent',
            'discount_value' => 100,
        ]);

        // Default Discount Codes
        DiscountCode::create([
            'code' => 'EARLYBIRD50',
            'promotion_id' => $earlyBird->id,
            'usage_limit' => 1000,
        ]);

        DiscountCode::create([
            'code' => 'KOMUNITAS50',
            'promotion_id' => $community->id,
            'usage_limit' => 500,
        ]);

        DiscountCode::create([
            'code' => 'STAFF100',
            'promotion_id' => $staff->id,
            'usage_limit' => 100,
        ]);

        DiscountCode::create([
            'code' => 'INFLUENCER100',
            'promotion_id' => $influencer->id,
            'usage_limit' => 50,
        ]);
    }
}
