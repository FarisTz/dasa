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
        Schema::table('applicants', function (Blueprint $table) {
            // Next of Kin Information
            $table->string('kin_full_name')->nullable();
            $table->enum('kin_relationship', ['father', 'mother', 'uncle', 'guardian'])->nullable();
            $table->string('kin_phone_number')->nullable();
            $table->enum('kin_religion', ['muslim', 'christian'])->nullable();
            $table->text('kin_address')->nullable();
            $table->string('kin_region')->nullable();
            $table->string('kin_district')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn([
                'kin_full_name',
                'kin_relationship', 
                'kin_phone_number',
                'kin_religion',
                'kin_address',
                'kin_region',
                'kin_district'
            ]);
        });
    }
};
