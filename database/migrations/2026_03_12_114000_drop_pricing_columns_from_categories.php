<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['price', 'early_bird_price', 'early_bird_deadline']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->decimal('price', 12, 2)->nullable();
            $table->decimal('early_bird_price', 12, 2)->nullable();
            $table->dateTime('early_bird_deadline')->nullable();
        });
    }
};
