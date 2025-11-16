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
        Schema::create('treatment_courses', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('dentist_id')->nullable()->constrained('users')->nullOnDelete();

            // Course info
            $table->date('started_at')->nullable();
            $table->date('completed_at')->nullable();
            $table->text('notes')->nullable();

            // Financial
            $table->decimal('total_cost', 10, 2)->default(0);

            // Lifecycle
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_courses');
    }
};
