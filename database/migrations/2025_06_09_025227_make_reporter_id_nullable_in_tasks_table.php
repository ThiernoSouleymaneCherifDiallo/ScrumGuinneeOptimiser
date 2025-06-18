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
        Schema::table('tasks', function (Blueprint $table) {
            // Vérifier si la contrainte existe avant de la supprimer
            $foreignKeys = Schema::getConnection()
                ->getDoctrineSchemaManager()
                ->listTableForeignKeys('tasks');
            
            $constraintExists = false;
            foreach ($foreignKeys as $foreignKey) {
                if (in_array('reporter_id', $foreignKey->getLocalColumns())) {
                    $constraintExists = true;
                    break;
                }
            }
            
            if ($constraintExists) {
                $table->dropForeign(['reporter_id']);
            }
            
            // Recréer la colonne en tant que nullable
            $table->foreignId('reporter_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete()
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Supprimer la contrainte de clé étrangère nullable
            $table->dropForeign(['reporter_id']);
            
            // Recréer la colonne en tant que non nullable
            $table->foreignId('reporter_id')
                  ->constrained('users')
                  ->cascadeOnDelete()
                  ->change();
        });
    }
};
