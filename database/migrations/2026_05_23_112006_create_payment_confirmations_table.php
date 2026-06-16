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
        Schema::create('payment_confirmations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')
                  ->constrained('payments')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreignId('beneficiary_id')
                  ->constrained('beneficiaries')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreignId('signed_by_user_id')
                  ->constrained('users')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            $table->string('otp', 10)->nullable();
            $table->enum('confirmation_method', ['digital', 'otp', 'manual']);
            $table->text('confirmation_note')->nullable();
            $table->timestamp('confirmed_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_confirmations');
    }
};
