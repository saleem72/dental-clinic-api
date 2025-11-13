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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('treatment_course_id')->nullable()->constrained('treatment_courses')->nullOnDelete();

            // Payment details
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->text('notes')->nullable();

            // Optional tracking fields
            $table->enum('method', ['cash', 'card', 'transfer'])->nullable();
            $table->string('reference_number')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
