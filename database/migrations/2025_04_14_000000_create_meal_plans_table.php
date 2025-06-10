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
        Schema::create('meal_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('meals'); // JSON structure for meals
            $table->integer('duration_days')->default(7);
            $table->decimal('total_calories', 8, 2)->nullable();
            $table->decimal('total_protein', 8, 2)->nullable();
            $table->decimal('total_carbs', 8, 2)->nullable();
            $table->decimal('total_fat', 8, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_plans');
    }
}; 