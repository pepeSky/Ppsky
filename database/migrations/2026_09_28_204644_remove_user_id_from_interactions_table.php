<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);

            $table->dropUnique([
                'user_id',
                'actor_a_id',
                'actor_b_id',
                'cohesion_id'
            ]);

            $table->dropColumn('user_id');

            $table->unique([
                'actor_a_id',
                'actor_b_id',
                'cohesion_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('interactions', function (Blueprint $table) {
            $table->dropUnique([
                'actor_a_id',
                'actor_b_id',
                'cohesion_id'
            ]);

            $table->foreignId('user_id')->after('id');

            $table->unique([
                'user_id',
                'actor_a_id',
                'actor_b_id',
                'cohesion_id'
            ]);

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }
};
