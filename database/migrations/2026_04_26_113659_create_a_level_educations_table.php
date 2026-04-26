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
        Schema::create('a_level_educations', function (Blueprint $table) {
            $table->id();
            
            // A-Level Education Information
            $table->string('school_name');
            $table->string('form_six_index_number')->unique();
            $table->string('division');
            $table->integer('points');
            $table->integer('end_of_study_year');
            $table->string('preferred_university');
            $table->string('form_six_certificate_path')->nullable();
            
            // Foreign key relationship
            $table->foreignId('applicant_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('a_level_educations');
    }
};
