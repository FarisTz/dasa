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
        // Create support replies table
        Schema::create('support_replies', function (Blueprint $table) {
            $table->id();

            // Foreign key to support tickets
            $table->foreignId('ticket_id')
                ->constrained('support_tickets')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // User who created the reply
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // Reply message
            $table->text('message');

            // Whether the reply is from admin
            $table->boolean('is_admin')->default(false);

            // Timestamps
            $table->timestamps();

            // Indexes for performance
            $table->index('ticket_id');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_replies');
    }
};
