<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('task', function (Blueprint $table) {
            $table->dropColumn([
                'priority_id',
                'date_expiration',
                'tag',
                'status',
                'module_id',
            ]);

            $table->renameColumn('task', 'name');
            $table->renameColumn('comment', 'description');
        });

        Schema::table('task', function (Blueprint $table) {
            $table->foreignId('activity_id')
                ->after('id')
                ->constrained('activities')
                ->cascadeOnDelete();

            $table->foreignId('context_id')
                ->after('activity_id')
                ->constrained('contexts')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('task', function (Blueprint $table) {
            $table->dropForeign(['activity_id']);
            $table->dropForeign(['context_id']);

            $table->dropColumn([
                'activity_id',
                'context_id',
            ]);
        });

        Schema::table('task', function (Blueprint $table) {
            $table->renameColumn('name', 'task');
            $table->renameColumn('description', 'comment');

            $table->string('priority_id');
            $table->string('date_expiration');
            $table->string('tag');
            $table->string('status');
            $table->string('module_id');
        });
    }
};
