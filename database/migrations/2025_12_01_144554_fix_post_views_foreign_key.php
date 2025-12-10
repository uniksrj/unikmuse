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
       Schema::table('post_views', function (Blueprint $table) {
            // First, drop the existing foreign key
            $table->dropForeign(['post_id']);
        });
        
        // Add the correct foreign key
        Schema::table('post_views', function (Blueprint $table) {
            $table->foreign('post_id')
                  ->references('id')
                  ->on('newpost_details')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('post_views', function (Blueprint $table) {
            $table->dropForeign(['post_id']);
            
            // Revert to original (if needed)
            $table->foreign('post_id')
                  ->references('id')
                  ->on('posts')
                  ->onDelete('cascade');
        });
    }
};
