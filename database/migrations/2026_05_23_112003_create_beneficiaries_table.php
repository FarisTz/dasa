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
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
             $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreignId('application_id')
                  ->unique()
                  ->constrained('applications')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreignId('scholarship_id')
                  ->constrained('scholarships')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->enum('award_type', ['full', 'partial']);
            $table->enum('confirmation_status', ['pending', 'confirmed', 'not_confirmed', 'canceled'])
                  ->default('pending');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->date('response_deadline');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiaries');
    }
};
