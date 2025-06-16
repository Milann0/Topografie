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
        Schema::table('games', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('games', 'total_questions')) {
                $table->integer('total_questions')->after('score')->default(0);
            }
            
            if (!Schema::hasColumn('games', 'game_type')) {
                $table->string('game_type')->after('total_questions')->default('countries');
            }
            
            if (!Schema::hasColumn('games', 'percentage')) {
                $table->decimal('percentage', 5, 2)->after('game_type')->default(0.00);
            }
        });
        
        // Add indexes separately to avoid conflicts
        try {
            Schema::table('games', function (Blueprint $table) {
                $table->index(['user_id', 'created_at'], 'games_user_created_index');
            });
        } catch (\Exception $e) {
            // Index might already exist, ignore error
        }
        
        try {
            Schema::table('games', function (Blueprint $table) {
                $table->index(['game_type', 'score'], 'games_type_score_index');
            });
        } catch (\Exception $e) {
            // Index might already exist, ignore error
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            // Drop indexes first
            try {
                $table->dropIndex('games_user_created_index');
            } catch (\Exception $e) {
                // Ignore if index doesn't exist
            }
            
            try {
                $table->dropIndex('games_type_score_index');
            } catch (\Exception $e) {
                // Ignore if index doesn't exist
            }
            
            // Drop columns if they exist
            if (Schema::hasColumn('games', 'total_questions')) {
                $table->dropColumn('total_questions');
            }
            
            if (Schema::hasColumn('games', 'game_type')) {
                $table->dropColumn('game_type');
            }
            
            if (Schema::hasColumn('games', 'percentage')) {
                $table->dropColumn('percentage');
            }
        });
    }
};