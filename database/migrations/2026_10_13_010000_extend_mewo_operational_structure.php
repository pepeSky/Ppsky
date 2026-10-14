<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('context_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('context_id')->constrained('contexts')->cascadeOnDelete();
            $table->foreignId('component_id')->constrained('contexts')->restrictOnDelete();
            $table->unsignedSmallInteger('position');
            $table->unique(['context_id', 'position']);
        });
        Schema::create('operational_verbs', function (Blueprint $table) {
            $table->id();
            $table->string('infinitive')->unique();
            $table->json('forms')->nullable();
            $table->timestamps();
        });
        Schema::create('mewo_process_operational_verb', function (Blueprint $table) {
            $table->foreignId('mewo_process_id')->constrained()->cascadeOnDelete();
            $table->foreignId('operational_verb_id')->constrained()->restrictOnDelete();
            $table->primary(['mewo_process_id', 'operational_verb_id'], 'mewo_process_verb_pk');
        });
        Schema::table('mewo_processes', function (Blueprint $table) {
            $table->text('input')->nullable();
            $table->text('output')->nullable();
            $table->text('procedure')->nullable();
        });
        Schema::create('activity_context', function (Blueprint $table) {
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('context_id')->constrained()->restrictOnDelete();
            $table->primary(['activity_id', 'context_id']);
        });
        Schema::create('activity_mewo_process', function (Blueprint $table) {
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mewo_process_id')->constrained()->restrictOnDelete();
            $table->primary(['activity_id', 'mewo_process_id']);
        });
        Schema::table('task', function (Blueprint $table) {
            $table->foreignId('mewo_process_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained()->restrictOnDelete();
            $table->decimal('quantity', 14, 4)->default(0);
            $table->string('status')->default('pending');
        });

        // Reuse existing expanded contexts without deleting or renaming them.
        $simple = DB::table('contexts')->where('type', 'simple')->pluck('id', 'code');
        foreach (DB::table('contexts')->where('type', 'ampliado')->get() as $context) {
            $codes = explode('+', $context->code);
            if (count($codes) > 1 && collect($codes)->every(fn ($code) => isset($simple[$code]))) {
                foreach ($codes as $position => $code) {
                    DB::table('context_components')->insert([
                        'context_id' => $context->id, 'component_id' => $simple[$code], 'position' => $position,
                    ]);
                }
            }
        }
        foreach (DB::table('task')->select('activity_id', 'context_id')->distinct()->get() as $task) {
            DB::table('activity_context')->insertOrIgnore((array) $task);
            foreach (DB::table('context_components')->where('context_id', $task->context_id)->pluck('component_id') as $componentId) {
                DB::table('activity_context')->insertOrIgnore(['activity_id' => $task->activity_id, 'context_id' => $componentId]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('task', function (Blueprint $table) {
            $table->dropForeign(['mewo_process_id']);
            $table->dropForeign(['unit_id']);
            $table->dropColumn(['mewo_process_id', 'unit_id', 'quantity', 'status']);
        });
        Schema::dropIfExists('activity_mewo_process');
        Schema::dropIfExists('activity_context');
        Schema::table('mewo_processes', fn (Blueprint $table) => $table->dropColumn(['input', 'output', 'procedure']));
        Schema::dropIfExists('mewo_process_operational_verb');
        Schema::dropIfExists('operational_verbs');
        Schema::dropIfExists('context_components');
    }
};
