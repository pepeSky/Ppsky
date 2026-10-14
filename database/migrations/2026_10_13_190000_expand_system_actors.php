<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('identities', function (Blueprint $t) {
            foreach (['person_type','first_name','second_name','third_name','paternal_surname','maternal_surname','legal_name','nationality','identifier_country','identifier_kind','identifier_number','email','phone','address','city','region','country'] as $field) { if (!Schema::hasColumn('identities',$field)) { $t->string($field)->nullable(); } }
            if (!Schema::hasColumn('identities','birth_date')) { $t->date('birth_date')->nullable(); }
            if (!Schema::hasIndex('identities','identities_formal_identifier_unique')) { $t->unique(['identifier_country','identifier_kind','identifier_number'], 'identities_formal_identifier_unique'); }
        });
        Schema::table('system_user', function (Blueprint $t) { if (!Schema::hasColumn('system_user','application_status')) { $t->string('application_status')->nullable(); } });
        // MySQL needs a non-unique replacement before removing the index supporting this FK.
        // This is a separate schema operation so it exists when DROP INDEX executes.
        if (!Schema::hasIndex('actors','actors_identity_id_index')) {
            Schema::table('actors', function (Blueprint $t) { $t->index('identity_id','actors_identity_id_index'); });
        }
        if (Schema::hasIndex('actors','actors_identity_id_unique')) {
            Schema::table('actors', function (Blueprint $t) { $t->dropUnique('actors_identity_id_unique'); });
        }
        Schema::table('actors', function (Blueprint $t) {
            if (!Schema::hasColumn('actors','system_id')) { $t->foreignId('system_id')->nullable(); }
            if (!Schema::hasColumn('actors','origin_actor_id')) { $t->foreignId('origin_actor_id')->nullable(); }
            if (!Schema::hasColumn('actors','account_user_id')) { $t->foreignId('account_user_id')->nullable(); }
            if (!Schema::hasColumn('actors','unit_id')) { $t->foreignId('unit_id')->nullable(); }
            if (!Schema::hasColumn('actors','unit_symbol')) { $t->string('unit_symbol', 8)->nullable(); }
            if (!Schema::hasColumn('actors','type_position')) { $t->unsignedSmallInteger('type_position')->nullable(); }
            if (!Schema::hasColumn('actors','sequence')) { $t->unsignedBigInteger('sequence')->nullable(); }
            if (!Schema::hasColumn('actors','code')) { $t->string('code', 64)->nullable(); }
            if (!Schema::hasColumn('actors','participation_status')) { $t->string('participation_status')->default('pending'); }
            if (!Schema::hasColumn('actors','application_status')) { $t->string('application_status')->default('pending'); }
            if (!Schema::hasColumn('actors','archived_at')) { $t->timestamp('archived_at')->nullable(); }
            if (!Schema::hasIndex('actors','actors_system_id_code_unique')) { $t->unique(['system_id','code']); }
            if (!Schema::hasIndex('actors','actors_classification_unique')) { $t->unique(['system_id','unit_symbol','type_position','sequence'], 'actors_classification_unique'); }
        });
        foreach (['system_id'=>'systems','origin_actor_id'=>'actors','account_user_id'=>'users','unit_id'=>'units'] as $column=>$target) {
            $present=collect(Schema::getForeignKeys('actors'))->contains(fn($key)=>$key['columns']===[$column]);
            if (!$present) {
                Schema::table('actors', function (Blueprint $t) use($column,$target) { $t->foreign($column)->references('id')->on($target)->restrictOnDelete(); });
            }
        }
        if (!Schema::hasTable('actor_sequences')) {
            Schema::create('actor_sequences', function (Blueprint $t) {
                $t->foreignId('system_id')->constrained()->restrictOnDelete();
                $t->string('unit_symbol',8); $t->unsignedSmallInteger('type_position');
                $t->unsignedBigInteger('last_number')->default(0);
                $t->primary(['system_id','unit_symbol','type_position']);
            });
        }
        if (!Schema::hasTable('actor_links')) {
            Schema::create('actor_links', function (Blueprint $t) {
                $t->foreignId('parent_id')->constrained('actors')->restrictOnDelete();
                $t->foreignId('child_id')->constrained('actors')->restrictOnDelete();
                $t->primary(['parent_id','child_id']); $t->timestamps();
            });
        }
        if (!Schema::hasTable('actor_cohesion')) {
            Schema::create('actor_cohesion', function (Blueprint $t) {
                $t->foreignId('actor_id')->constrained()->restrictOnDelete();
                $t->foreignId('cohesion_id')->constrained()->restrictOnDelete();
                $t->primary(['actor_id','cohesion_id']); $t->timestamps();
            });
        }
        if (!Schema::hasTable('system_roles')) {
            Schema::create('system_roles', function (Blueprint $t) {
                $t->id(); $t->foreignId('system_id')->constrained()->restrictOnDelete();
                $t->string('name'); $t->json('permissions'); $t->timestamps(); $t->unique(['system_id','name']);
            });
        }
        if (!Schema::hasTable('system_role_user')) {
            Schema::create('system_role_user', function (Blueprint $t) {
                $t->foreignId('system_role_id')->constrained()->cascadeOnDelete();
                $t->foreignId('user_id')->constrained()->cascadeOnDelete();
                $t->primary(['system_role_id','user_id']); $t->timestamps();
            });
        }
        if (!Schema::hasTable('actor_changes')) {
            Schema::create('actor_changes', function (Blueprint $t) {
                $t->id(); $t->foreignId('system_id')->constrained()->restrictOnDelete();
                $t->foreignId('actor_id')->nullable()->constrained()->restrictOnDelete();
                $t->foreignId('user_id')->constrained()->restrictOnDelete();
                $t->string('action'); $t->json('changes'); $t->timestamps();
            });
        }
        foreach (['UnNE'=>'Negocio','UnSe'=>'Servicio','UnSo'=>'Soporte','UnOp'=>'Operativa','UnEl'=>'Elemental'] as $symbol=>$name) {
            $existing=\Illuminate\Support\Facades\DB::table('units')->whereIn('symbol', $symbol==='UnNE' ? ['UnNE','UnNe'] : [$symbol])->where(fn($q)=>$q->where('system','SyQuAc')->orWhereNull('system'))->first();
            if (!$existing) { \Illuminate\Support\Facades\DB::table('units')->insert(['name'=>$name,'symbol'=>$symbol,'system'=>'SyQuAc','magnitude'=>'Trabajo','type'=>'Monetaria','created_at'=>now(),'updated_at'=>now()]); }
        }
        // Keep legacy actors unassigned: membership does not prove which system owns a record.
    }

    public function down(): void
    {
        // A rollback must never silently erase actor codes, access decisions or relationships.
        throw new RuntimeException('Esta migración conserva datos de actores. Restaure una copia o prepare una reversión explícita.');
    }
};
