<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('full_name');
            $table->string('email');
            $table->string('phone', 20);
            $table->enum('gender', ['male', 'female']);
            $table->date('date_of_birth');
            $table->string('identity_number', 30)->nullable(); // KTP / Passport
            $table->string('nationality')->default('Indonesia');
            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('set null');
            $table->foreignId('province_id')->nullable()->constrained('provinces')->onDelete('set null');
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null');
            $table->string('address')->nullable();
            $table->string('blood_type', 3)->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone', 20)->nullable();
            $table->string('jersey_size', 5)->nullable();
            $table->string('community')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('bib_number', 10)->nullable();
            $table->boolean('checked_in')->default(false);
            $table->dateTime('checked_in_at')->nullable();
            $table->timestamps();

            // Composite unique index
            $table->unique(['event_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
