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
        Schema::create('additional_infos', function (Blueprint $table) {
            $table->id();
            
            // Additional Information
            $table->boolean('is_orphan')->default(false);
            $table->string('orphan_type')->nullable(); // Father, Mother, Both
            $table->string('orphan_certificate_path')->nullable();
            $table->text('motivation_message')->nullable();
            
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
        Schema::dropIfExists('additional_infos');
    }
};
