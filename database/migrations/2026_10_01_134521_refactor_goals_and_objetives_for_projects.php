<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('goals', function (Blueprint $table) {
            $table->dropForeign(['development_id']);
            $table->dropColumn('development_id');

            $table->foreignId('project_id')
                ->after('name')
                ->constrained()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('goals', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropColumn('project_id');

            $table->foreignId('development_id')
                ->after('name')
                ->constrained()
                ->cascadeOnDelete();
        });
    }
};
