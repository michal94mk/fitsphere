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
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('specialization')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('bio')->nullable();
            $table->string('specialties')->nullable();
            $table->unsignedInteger('experience')->default(0);
            $table->boolean('is_approved')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('trainer_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainer_id')->constrained()->onDelete('cascade');
            $table->string('locale', 2); // Language code (en, pl)
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('bio')->nullable();
            $table->string('specialties')->nullable();
            $table->timestamps();
            
            // Make sure we don't have duplicate translations for the same trainer and locale
            $table->unique(['trainer_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainer_translations');
        Schema::dropIfExists('trainers');
    }
}; 