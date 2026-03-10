<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->string('jersey_size', 20)->nullable()->change();
        });

        Schema::table('family_members', function (Blueprint $table) {
            $table->string('jersey_size', 20)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->string('jersey_size', 5)->nullable()->change();
        });

        Schema::table('family_members', function (Blueprint $table) {
            $table->string('jersey_size', 5)->nullable()->change();
        });
    }
};
