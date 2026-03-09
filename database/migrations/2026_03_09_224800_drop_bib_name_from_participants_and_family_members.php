<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            if (Schema::hasColumn('participants', 'bib_name')) {
                $table->dropColumn('bib_name');
            }
        });

        Schema::table('family_members', function (Blueprint $table) {
            if (Schema::hasColumn('family_members', 'bib_name')) {
                $table->dropColumn('bib_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->string('bib_name')->nullable();
        });

        Schema::table('family_members', function (Blueprint $table) {
            $table->string('bib_name')->nullable();
        });
    }
};
