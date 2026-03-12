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
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('promotion_id')->nullable()->after('discount_code_id');
            $table->foreign('promotion_id')->references('id')->on('promotions')->onDelete('set null');
        });
        
        // Add used_count to promotions table if it doesn't exist (checking Promotion model shows it might be needed for logic)
        // Actually, checking previous files, promotions table was created in 2026_03_12_111601_create_promotions_and_discount_codes_tables.php
        // Let's check that file content again to see if used_count exists.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['promotion_id']);
            $table->dropColumn('promotion_id');
        });
    }
};
