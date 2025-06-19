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
            if (!Schema::hasColumn('project_messages', 'is_edited')) {
                $table->boolean('is_edited')->default(false)->after('content');
            }
            if (!Schema::hasColumn('project_messages', 'edited_at')) {
                $table->timestamp('edited_at')->nullable()->after('is_edited');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_messages', function (Blueprint $table) {
            if (Schema::hasColumn('project_messages', 'is_edited')) {
                $table->dropColumn('is_edited');
            }
            if (Schema::hasColumn('project_messages', 'edited_at')) {
                $table->dropColumn('edited_at');
            }
        });
    }
};
