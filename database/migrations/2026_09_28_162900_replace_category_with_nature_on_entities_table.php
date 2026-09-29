<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('entities', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::table('entities', function (Blueprint $table) {
            $table->foreignId('nature_id')
                ->after('code')
                ->constrained('natures')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('entities', function (Blueprint $table) {
            $table->dropForeign(['nature_id']);
            $table->dropColumn('nature_id');
        });

        Schema::table('entities', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')
                ->after('code');

            $table->foreign('category_id')
                ->references('id')
                ->on('entities')
                ->cascadeOnDelete();
        });
    }
};