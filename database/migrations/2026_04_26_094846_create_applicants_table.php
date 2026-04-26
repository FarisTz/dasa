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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            
            // Basic Personal Information
            $table->string('full_name');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->date('birthdate');
            $table->string('place_of_birth');
            $table->string('nationality');
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed']);
            $table->string('religion');
            
            // Contact Information
            $table->text('address');
            $table->string('region');
            $table->string('district');
            $table->string('email')->unique();
            $table->string('phone_number');
            
            // Identification Details
            $table->string('zanzibar_national_id')->unique()->nullable();
            $table->string('passport_number')->unique()->nullable();
            
            // Additional Information & Documents
            $table->boolean('disability')->default(false);
            $table->string('birth_certificate_path')->nullable();
            
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
