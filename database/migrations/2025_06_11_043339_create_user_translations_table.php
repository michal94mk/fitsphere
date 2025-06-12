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
        Schema::create('user_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('locale', 2); // 'en', 'pl', etc.
            $table->string('name')->nullable();
            $table->string('specialization')->nullable();
            $table->text('description')->nullable();
            $table->text('bio')->nullable();
            $table->string('specialties')->nullable();
            $table->timestamps();
            
            // Ensure unique locale per user
            $table->unique(['user_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_translations');
    }
};
