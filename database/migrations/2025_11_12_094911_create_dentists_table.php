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
        Schema::create('dentists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();

            // Professional details
            $table->string('license_number')->nullable();
            $table->string('specialization')->nullable(); // e.g. "Orthodontist"
            $table->text('bio')->nullable(); // short profile/bio

            // Work-related
            $table->decimal('commission_rate', 5, 2)->nullable(); // optional, % for internal use
            $table->boolean('is_available')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dentists');
    }
};
