<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mewo_processes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('context_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['context_id', 'code']);
            $table->unique(['context_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mewo_processes');
    }
};
