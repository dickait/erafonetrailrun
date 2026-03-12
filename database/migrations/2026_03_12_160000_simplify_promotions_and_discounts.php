<?php
/** @noinspection ALL */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('promotions', 'code')) {
            Schema::table('promotions', function (Blueprint $table) {
                $table->string('code')->nullable()->after('name');
            });
        }

        // Drop foreign key and column from payments first
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'discount_code_id')) {
                // Try to find the foreign key name. Usually it's payments_discount_code_id_foreign
                try {
                    $table->dropForeign(['discount_code_id']);
                } catch (\Exception $e) {
                    // Ignore if foreign key doesn't exist
                }
                $table->dropColumn('discount_code_id');
            }
        });

        // Now safe to drop the table
        Schema::dropIfExists('discount_codes');
    }

    public function down()
    {
        if (!Schema::hasTable('discount_codes')) {
            Schema::create('discount_codes', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->foreignId('promotion_id')->constrained()->onDelete('cascade');
                $table->integer('usage_limit')->nullable();
                $table->integer('used_count')->default(0);
                $table->timestamps();
            });
        }

        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'discount_code_id')) {
                $table->unsignedBigInteger('discount_code_id')->nullable()->after('promotion_id');
                $table->foreign('discount_code_id')->references('id')->on('discount_codes')->onDelete('set null');
            }
        });

        Schema::table('promotions', function (Blueprint $table) {
            if (Schema::hasColumn('promotions', 'code')) {
                $table->dropColumn('code');
            }
        });
    }
};
