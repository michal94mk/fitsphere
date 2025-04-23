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
        Schema::create('trainer_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainer_id')->constrained()->onDelete('cascade');
            $table->string('locale', 2); // Kod języka (en, pl)
            $table->string('specialization')->nullable();
            $table->text('description')->nullable();
            $table->text('bio')->nullable();
            $table->string('specialties')->nullable();
            $table->timestamps();
            
            // Upewniamy się, że nie ma duplikatów tłumaczeń dla tego samego trenera i języka
            $table->unique(['trainer_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainer_translations');
    }
}; 