<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_units', function (Blueprint $table) {
            $table->id();

            $table->foreignId('system_id')
                ->constrained('systems')
                ->cascadeOnDelete();

            $table->foreignId('actor_id')
                ->constrained('actors')
                ->cascadeOnDelete();

            $table->foreignId('unit_id')
                ->constrained('units')
                ->restrictOnDelete();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('work_units')
                ->nullOnDelete();

            $table->timestamps();

            $table->unique(
                ['system_id', 'actor_id', 'unit_id'],
                'work_units_scope_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_units');
    }
};
