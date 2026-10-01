<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('objetives', function (Blueprint $table) {
            $table->dropForeign(['requirement_id']);
            $table->dropColumn('requirement_id');
        });
    }

    public function down(): void
    {
        Schema::table('objetives', function (Blueprint $table) {
            $table->foreignId('requirement_id')
                ->after('goal_id')
                ->constrained()
                ->cascadeOnDelete();
        });
    }
};
