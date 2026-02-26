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
            $table->integer('elevation')->nullable()->after('distance_km');
            $table->integer('cot')->nullable()->after('elevation'); // In hours or minutes, let's say integer hours
            $table->decimal('effort_km', 8, 2)->nullable()->after('cot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['elevation', 'cot', 'effort_km']);
        });
    }
};
