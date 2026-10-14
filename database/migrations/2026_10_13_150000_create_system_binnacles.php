<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('binnacles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->constrained()->restrictOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->date('recorded_on');
            $table->string('title');
            $table->text('summary')->nullable();
            $table->timestamps();
            $table->index(['system_id', 'recorded_on']);
        });
        Schema::create('proposition_system', function (Blueprint $table) {
            $table->foreignId('proposition_id')->unique()->constrained()->restrictOnDelete();
            $table->foreignId('system_id')->constrained()->restrictOnDelete();
            $table->primary(['proposition_id', 'system_id']);
        });
        foreach (['proposition' => 'propositions', 'model' => 'models', 'activity' => 'activities'] as $relation => $target) {
            Schema::create('binnacle_'.$relation, function (Blueprint $table) use ($relation, $target) {
                $table->foreignId('binnacle_id')->constrained()->cascadeOnDelete();
                $table->foreignId($relation.'_id')->constrained($target)->restrictOnDelete();
                $table->primary(['binnacle_id', $relation.'_id']);
            });
        }
    }

    public function down(): void
    {
        foreach (['activity', 'model', 'proposition'] as $relation) {
            Schema::dropIfExists('binnacle_'.$relation);
        }
        Schema::dropIfExists('proposition_system');
        Schema::dropIfExists('binnacles');
    }
};
