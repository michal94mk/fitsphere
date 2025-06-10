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
        Schema::create('nutritional_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('age')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->decimal('height', 5, 2)->nullable(); // in cm
            $table->decimal('weight', 5, 2)->nullable(); // in kg
            $table->enum('activity_level', ['sedentary', 'lightly_active', 'moderately_active', 'very_active', 'extremely_active'])->nullable();
            $table->enum('goal', ['lose_weight', 'maintain_weight', 'gain_weight', 'build_muscle', 'improve_health'])->nullable();
            $table->json('dietary_restrictions')->nullable(); // allergies, preferences, etc.
            $table->decimal('target_calories', 8, 2)->nullable();
            $table->decimal('target_protein', 8, 2)->nullable();
            $table->decimal('target_carbs', 8, 2)->nullable();
            $table->decimal('target_fat', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nutritional_profiles');
    }
}; 