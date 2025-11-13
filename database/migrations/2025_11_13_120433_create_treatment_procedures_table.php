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
        Schema::create('treatment_procedures', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('treatment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dental_procedure_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dentist_id')->nullable()->constrained('users')->nullOnDelete();

            // Optional fields
            $table->date('performed_at')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_procedures');
    }
};
