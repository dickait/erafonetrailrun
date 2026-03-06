<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('family_members', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('participant_id')->comment('The team leader / primary participant');
            $table->foreign('participant_id')->references('id')->on('participants')->onDelete('cascade');

            $table->string('full_name');
            $table->string('bib_name', 20);
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->date('date_of_birth');
            $table->string('identity_number', 30)->nullable(); // KTP/KIA/Passport

            // For location/address we can assume they might share the leader's address 
            // but we store it just in case it's different.
            $table->string('nationality')->default('Indonesia');
            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('set null');
            $table->char('province_id', 2)->nullable();
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('set null');
            $table->char('city_id', 4)->nullable();
            $table->foreign('city_id')->references('id')->on('regencies')->onDelete('set null');
            $table->string('address')->nullable();

            $table->string('blood_type', 3)->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone', 20)->nullable();
            $table->string('jersey_size', 5)->nullable();
            $table->string('community')->nullable();
            $table->text('medical_conditions')->nullable();

            $table->string('bib_number', 10)->nullable();
            $table->boolean('checked_in')->default(false);
            $table->dateTime('checked_in_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('family_members');
    }
};
