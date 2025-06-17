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
        Schema::create('epics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('À faire');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('priority')->default('Moyenne');
            $table->timestamps();
        });

        // Ajouter la colonne epic_id à la table tasks
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('epic_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['epic_id']);
            $table->dropColumn('epic_id');
        });
        
        Schema::dropIfExists('epics');
    }
}; 