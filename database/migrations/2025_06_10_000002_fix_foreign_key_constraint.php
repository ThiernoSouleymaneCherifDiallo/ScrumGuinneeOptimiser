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
        // Cette migration n'est plus nécessaire car nous avons corrigé le problème dans la migration précédente
        // Elle est gardée pour la compatibilité mais ne fait rien
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cette migration n'est plus nécessaire car nous avons corrigé le problème dans la migration précédente
        // Elle est gardée pour la compatibilité mais ne fait rien
    }
}; 