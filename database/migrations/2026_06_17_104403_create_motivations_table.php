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
        Schema::create('motivations', function (Blueprint $table) {
            $table->id();
                $table->foreignId('user_id')
                    ->constrained('users')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            $table->text('motivation_letter');
            $table->text('academic_goals')->nullable();
            $table->text('community_contribution')->nullable();
            $table->text('additional_information')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motivations');
    }
};
