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
        Schema::create('interactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('actor_a_id')
                ->constrained('actors')
                ->cascadeOnDelete();

            $table->foreignId('actor_b_id')
                ->constrained('actors')
                ->cascadeOnDelete();

            $table->foreignId('cohesion_id')
                ->constrained('cohesions')
                ->restrictOnDelete();

            $table->timestamps();

            $table->unique([
                'user_id',
                'actor_a_id',
                'actor_b_id',
                'cohesion_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interactions');
    }
};
