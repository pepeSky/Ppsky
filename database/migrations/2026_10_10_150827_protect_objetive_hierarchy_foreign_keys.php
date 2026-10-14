<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('objetives', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropForeign(['goal_id']);
        });

        Schema::table('objetives', function (Blueprint $table) {
            $table->foreign('parent_id')
                ->references('id')
                ->on('objetives')
                ->restrictOnDelete();

            $table->foreign('goal_id')
                ->references('id')
                ->on('goals')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('objetives', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropForeign(['goal_id']);
        });

        Schema::table('objetives', function (Blueprint $table) {
            $table->foreign('parent_id')
                ->references('id')
                ->on('objetives')
                ->cascadeOnDelete();

            $table->foreign('goal_id')
                ->references('id')
                ->on('goals')
                ->cascadeOnDelete();
        });
    }
};
