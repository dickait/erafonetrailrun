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
        Schema::table('participants', function (Blueprint $table) {
            $table->boolean('rpc')->default(false)->after('checked_in_at');
        });

        Schema::table('family_members', function (Blueprint $table) {
            $table->boolean('rpc')->default(false)->after('checked_in_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn('rpc');
        });

        Schema::table('family_members', function (Blueprint $table) {
            $table->dropColumn('rpc');
        });
    }
};
