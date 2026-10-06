<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('goals', function (Blueprint $table) {
            $table->foreignId('plan_id')
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();
        });

        Schema::table('objetives', function (Blueprint $table) {
            $table->foreignId('plan_id')
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('parent_id')
                ->nullable()
                ->after('goal_id')
                ->constrained('objetives')
                ->cascadeOnDelete();

            $table->boolean('is_general')
                ->default(false)
                ->after('parent_id');
        });
    }

    public function down(): void
    {
        Schema::table('objetives', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropForeign(['plan_id']);
            $table->dropColumn([
                'parent_id',
                'plan_id',
                'is_general',
            ]);
        });

        Schema::table('goals', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->dropColumn('plan_id');
        });
    }
};
