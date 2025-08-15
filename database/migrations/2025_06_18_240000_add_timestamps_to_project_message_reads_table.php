<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Vérifier si les colonnes timestamps existent déjà
        if (!Schema::hasColumn('project_message_reads', 'created_at')) {
            Schema::table('project_message_reads', function (Blueprint $table) {
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Vérifier si les colonnes existent avant de les supprimer
        if (Schema::hasColumn('project_message_reads', 'created_at')) {
            Schema::table('project_message_reads', function (Blueprint $table) {
                $table->dropTimestamps();
            });
        }
    }
}; 