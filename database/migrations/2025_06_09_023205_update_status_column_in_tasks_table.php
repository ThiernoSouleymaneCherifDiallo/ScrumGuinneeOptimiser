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
        Schema::table('tasks', function (Blueprint $table) {
            // Mettre à jour les valeurs existantes
            DB::table('tasks')->where('status', 'open')->update(['status' => 'todo']);
            DB::table('tasks')->where('status', 'closed')->update(['status' => 'done']);
            
            // Modifier la colonne status pour utiliser les nouvelles valeurs
            DB::statement("ALTER TABLE tasks MODIFY status ENUM('todo', 'in_progress', 'review', 'done') NOT NULL DEFAULT 'todo'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Modifier la colonne pour accepter les anciennes valeurs
            DB::statement("ALTER TABLE tasks MODIFY status ENUM('open', 'in_progress', 'done', 'closed') NOT NULL DEFAULT 'open'");
            
            // Revenir aux anciennes valeurs
            DB::table('tasks')->where('status', 'todo')->update(['status' => 'open']);
            DB::table('tasks')->where('status', 'review')->update(['status' => 'in_progress']);
        });
    }
};
