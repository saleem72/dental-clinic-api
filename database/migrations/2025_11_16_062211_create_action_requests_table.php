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
        Schema::create('action_requests', function (Blueprint $table) {
            $table->id();

            // Useful references
            $table->foreignId('created_by_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_to_id')->constrained('users')->cascadeOnDelete()->nullable();

            // Optional patient treatment context
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->foreignId('treatment_course_id')->nullable()->constrained('treatment_courses')->nullOnDelete();
            $table->foreignId('treatment_session_id')->nullable()->constrained('treatment_sessions')->nullOnDelete();

            // Enums
            $table->string('type');   // via Enum
            $table->string('status'); // via Enum

            // Extra data (like new date for rescheduling, reason, notes)
            $table->json('payload')->nullable();

            // Doctor’s response/notes
            $table->text('doctor_note')->nullable();

            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_requests');
    }
};
