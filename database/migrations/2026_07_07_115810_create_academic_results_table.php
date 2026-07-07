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
        Schema::create('academic_results', function (Blueprint $table) {
            $table->id();

            // Student who uploaded the result
            $table->foreignId('student_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // Academic year
            $table->string('academic_year', 20); // e.g., 2023/2024

            // Student year (1,2,3,4...)
            $table->unsignedTinyInteger('student_year');

            // Result details
            $table->string('course_name', 255)->nullable();
            $table->decimal('gpa', 3, 2)->nullable(); // Grade Point Average

            $table->string('division', 20)->nullable(); // First Class, Second Class, etc.
            $table->text('remarks')->nullable();

            // Result file upload
            $table->string('result_file_path', 500)->nullable();
           

            // Status tracking
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected', 'resubmit'])->default('pending');

            // Admin feedback
            $table->text('admin_feedback')->nullable();
            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->timestamp('reviewed_at')->nullable();

            // Suspension tracking
            $table->boolean('is_suspended')->default(false);
            $table->text('suspension_reason')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->timestamp('suspension_lifted_at')->nullable();

            $table->timestamps();

            // Indexes for performance
            $table->index(['student_id', 'academic_year']);
            $table->index('status');
            $table->index('is_suspended');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_results');
    }
};
