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
        Schema::table('project_messages', function (Blueprint $table) {
            $table->string('filename')->nullable()->after('content');
            $table->string('original_name')->nullable()->after('filename');
            $table->string('file_path')->nullable()->after('original_name');
            $table->integer('file_size')->nullable()->after('file_path'); // Taille en bytes
            $table->string('file_type')->nullable()->after('file_size'); // MIME type
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_messages', function (Blueprint $table) {
            $table->dropColumn(['filename', 'original_name', 'file_path', 'file_size', 'file_type']);
        });
    }
};
