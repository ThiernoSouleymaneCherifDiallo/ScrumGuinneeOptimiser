<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Vérifier si la contrainte existe avant de la supprimer
        $constraints = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.TABLE_CONSTRAINTS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'tasks' 
            AND CONSTRAINT_NAME = 'tasks_reporter_id_foreign'
        ");

        if (!empty($constraints)) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropForeign(['reporter_id']);
            });
        }

        // Modifier la colonne pour la rendre nullable
        DB::statement('ALTER TABLE tasks MODIFY reporter_id BIGINT UNSIGNED NULL');
        
        // Recréer la contrainte de clé étrangère
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreign('reporter_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer la contrainte de clé étrangère
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['reporter_id']);
        });

        // Modifier la colonne pour la rendre non nullable
        DB::statement('ALTER TABLE tasks MODIFY reporter_id BIGINT UNSIGNED NOT NULL');
        
        // Recréer la contrainte de clé étrangère
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreign('reporter_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
