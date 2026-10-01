<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->foreignId('project_id')
                ->after('id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('name')
                ->after('project_id');

            $table->text('description')
                ->nullable()
                ->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign(['project_id']);

            $table->dropColumn([
                'project_id',
                'name',
                'description',
            ]);
        });
    }
};
