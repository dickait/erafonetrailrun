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
        Schema::create('race_results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('participant_id')->constrained('participants')->onDelete('cascade');
            $table->string('bib_number', 10)->index();
            $table->string('gun_time')->nullable();
            $table->string('net_time')->nullable();
            $table->decimal('distance_km', 5, 2)->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('age_category')->nullable(); // Open, Master
            $table->integer('rank_overall')->nullable();
            $table->integer('rank_category')->nullable();
            $table->integer('rank_group')->nullable();
            $table->boolean('is_podium')->default(false);
            $table->string('status')->default('FINISHED');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('race_results');
    }
};
