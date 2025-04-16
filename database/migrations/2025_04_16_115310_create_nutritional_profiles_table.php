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
            $table->decimal('weight', 5, 2)->nullable(); // w kilogramach
            $table->decimal('height', 5, 2)->nullable(); // w centymetrach
            $table->enum('activity_level', ['sedentary', 'light', 'moderate', 'active', 'very_active'])->nullable();
            $table->enum('goal', ['maintain', 'lose', 'gain'])->nullable();
            $table->decimal('target_calories', 7, 2)->nullable();
            $table->decimal('target_protein', 7, 2)->nullable(); // w gramach
            $table->decimal('target_carbs', 7, 2)->nullable(); // w gramach
            $table->decimal('target_fat', 7, 2)->nullable(); // w gramach
            $table->json('dietary_restrictions')->nullable(); // np. wegetarianizm, bezglutenowa itp.
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
