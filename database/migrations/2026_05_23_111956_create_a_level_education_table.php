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
        Schema::create('a_level_education', function (Blueprint $table) {
            $table->id();
             $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->string('school_name', 255);
            $table->string('form_six_index_number', 100);
            $table->string('division', 10)->nullable();
            $table->integer('points')->nullable();
            $table->year('end_of_study_year')->nullable();
            $table->string('preferred_university', 255)->nullable();
            $table->string('form_six_certificate_path', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('a_level_education');
    }
};
