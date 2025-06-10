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

        // Create comments table if it doesn't exist
        if (!Schema::hasTable('comments')) {
            Schema::create('comments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('post_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('trainer_id')->nullable()->constrained()->onDelete('cascade');
                $table->text('content');
                $table->timestamps();
                
                // Ensure either user_id or trainer_id is set, but not both
                $table->check('(user_id IS NOT NULL AND trainer_id IS NULL) OR (user_id IS NULL AND trainer_id IS NOT NULL)');
            });
        } else {
            // Add trainer_id column to existing comments table and make user_id nullable
            Schema::table('comments', function (Blueprint $table) {
                if (!Schema::hasColumn('comments', 'trainer_id')) {
                    $table->foreignId('trainer_id')->nullable()->after('user_id')->constrained()->onDelete('cascade');
                }
                // Make user_id nullable to allow trainer comments
                $table->unsignedBigInteger('user_id')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('comments')) {
            Schema::table('comments', function (Blueprint $table) {
                if (Schema::hasColumn('comments', 'trainer_id')) {
                    $table->dropForeign(['trainer_id']);
                    $table->dropColumn('trainer_id');
                }
                // Revert user_id back to not nullable
                $table->unsignedBigInteger('user_id')->nullable(false)->change();
            });
        }
        
        Schema::dropIfExists('trainer_translations');
        Schema::dropIfExists('trainers');
    }
}; 