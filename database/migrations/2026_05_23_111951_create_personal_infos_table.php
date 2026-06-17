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
        Schema::create('personal_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('birthdate')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('nationality', 100)->nullable();
            $table->enum('marital_status', ['single', 'married'])->nullable();
            $table->enum('religion', ['muslim', 'christian'])->nullable();
            $table->text('address')->nullable();
            $table->string('region', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->enum('id_type', ['National', 'zanID', 'Passport'])->nullable();
            $table->string('id_number', 100)->nullable();
            $table->string('disability')->nullable();
            $table->string('birth_certificate_path', 500)->nullable();
            $table->string('kin_full_name')->nullable();
            $table->enum('kin_relationship', ['father', 'mother', 'uncle', 'guardian'])->nullable();
            $table->string('kin_phone_number', 20)->nullable();
            $table->text('kin_address')->nullable();
            $table->string('kin_district', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_infos');
    }
};
