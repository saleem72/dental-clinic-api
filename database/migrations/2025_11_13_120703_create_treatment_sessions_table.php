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
        Schema::create('treatment_sessions', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('treatment_course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dentist_id')->nullable()->constrained('users')->nullOnDelete();

            // Session details
            $table->date('session_date')->nullable();
            $table->text('notes')->nullable();

            // Status & billing
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->decimal('total_cost', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_sessions');
    }
};
