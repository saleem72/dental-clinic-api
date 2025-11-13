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
        Schema::create('dental_procedures', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Comprehensive Oral Evaluation"
            $table->string('dental_code')->unique(); // e.g. D0150
            $table->decimal('fee', 8, 2)->default(0.00); // default or reference fee
            $table->text('description')->nullable(); // optional extra details
            $table->boolean('is_active')->default(true); // soft toggle for catalog
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dental_procedures');
    }
};
