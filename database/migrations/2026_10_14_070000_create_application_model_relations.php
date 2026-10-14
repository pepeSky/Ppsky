<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('application_model_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->restrictOnDelete();
            $table->foreignId('source_model_id')->constrained('models')->restrictOnDelete();
            $table->foreignId('target_model_id')->constrained('models')->restrictOnDelete();
            $table->string('type', 40);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['application_id', 'source_model_id', 'target_model_id', 'type'], 'app_model_relation_unique');
        });
    }
    public function down(): void { Schema::dropIfExists('application_model_relations'); }
};
