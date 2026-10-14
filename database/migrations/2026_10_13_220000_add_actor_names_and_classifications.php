<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('actors','name')) { Schema::table('actors',fn(Blueprint $t)=>$t->string('name')->nullable()); }
        if (!Schema::hasTable('actor_classifications')) {
            Schema::create('actor_classifications',function(Blueprint $t) {
                $t->id(); $t->foreignId('actor_id')->constrained()->restrictOnDelete();
                $t->foreignId('system_id')->constrained()->restrictOnDelete();
                $t->foreignId('unit_id')->constrained()->restrictOnDelete();
                $t->foreignId('cohesion_id')->nullable()->constrained()->restrictOnDelete();
                $t->string('unit_symbol',8); $t->unsignedSmallInteger('type_position');
                $t->unsignedBigInteger('sequence'); $t->string('code',64); $t->timestamps();
                $t->unique(['system_id','code']);
                $t->unique(['system_id','unit_symbol','type_position','sequence'],'actor_classifications_sequence_unique');
                $t->unique(['actor_id','unit_symbol','type_position'],'actor_classifications_role_unique');
            });
        }
        if (!Schema::hasTable('actor_classification_links')) {
            Schema::create('actor_classification_links',function(Blueprint $t) {
                $t->foreignId('parent_id')->constrained('actor_classifications')->restrictOnDelete();
                $t->foreignId('child_id')->constrained('actor_classifications')->restrictOnDelete();
                $t->primary(['parent_id','child_id']); $t->timestamps();
            });
        }
        DB::table('actors')->whereNotNull('system_id')->whereNotNull('code')->orderBy('id')->chunkById(100,function($actors) {
            foreach($actors as $actor) {
                if (!DB::table('actor_classifications')->where('actor_id',$actor->id)->where('code',$actor->code)->exists()) {
                DB::table('actor_classifications')->insert(['actor_id'=>$actor->id,'unit_symbol'=>$actor->unit_symbol,'type_position'=>$actor->type_position]+
                    ['system_id'=>$actor->system_id,'unit_id'=>$actor->unit_id,'cohesion_id'=>null,'sequence'=>$actor->sequence,'code'=>$actor->code,'created_at'=>$actor->created_at,'updated_at'=>$actor->updated_at]);
                }
                $key=['system_id'=>$actor->system_id,'unit_symbol'=>$actor->unit_symbol,'type_position'=>$actor->type_position];
                $series=DB::table('actor_sequences')->where($key)->first();
                if (!$series) { DB::table('actor_sequences')->insert($key+['last_number'=>$actor->sequence]); }
                elseif ($series->last_number<$actor->sequence) { DB::table('actor_sequences')->where($key)->update(['last_number'=>$actor->sequence]); }
            }
        });
    }
    public function down(): void
    {
        throw new RuntimeException('Conserve los nombres y códigos de actores; prepare una reversión explícita si necesita restaurar una versión anterior.');
    }
};
