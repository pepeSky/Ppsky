<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('binnacles', function (Blueprint $table) {
            $table->date('daily_date')->nullable();
            $table->unique(['system_id', 'daily_date']);
        });
        Schema::create('activity_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->date('scheduled_on');
            $table->timestamps();
            $table->unique(['activity_id', 'user_id', 'scheduled_on']);
        });
        Schema::create('interaction_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('interaction_id')->nullable()->constrained()->restrictOnDelete();
            $table->string('category');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->timestamps();
            $table->index(['user_id', 'starts_at', 'ends_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interaction_periods');
        Schema::dropIfExists('activity_programs');
        Schema::table('binnacles', function (Blueprint $table) {
            $table->dropUnique(['system_id', 'daily_date']);
            $table->dropColumn('daily_date');
        });
    }
};
