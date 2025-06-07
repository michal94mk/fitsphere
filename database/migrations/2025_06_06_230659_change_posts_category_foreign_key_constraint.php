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
        Schema::table('posts', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['category_id']);
            
            // Make category_id nullable (optional category)
            $table->unsignedBigInteger('category_id')->nullable()->change();
            
            // Add new foreign key constraint with RESTRICT (prevent deletion of category with posts)
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Drop the current foreign key constraint
            $table->dropForeign(['category_id']);
            
            // Make category_id not nullable again
            $table->unsignedBigInteger('category_id')->nullable(false)->change();
            
            // Restore the original foreign key constraint with CASCADE
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }
};
