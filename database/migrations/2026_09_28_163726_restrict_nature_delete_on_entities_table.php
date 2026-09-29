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
        Schema::table('entities', function (Blueprint $table) {
            $table->dropForeign(['nature_id']);

            $table->foreign('nature_id')
                ->references('id')
                ->on('natures')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entities', function (Blueprint $table) {
            $table->dropForeign(['nature_id']);

            $table->foreign('nature_id')
                ->references('id')
                ->on('natures')
                ->cascadeOnDelete();
        });
    }
};
