<?php
namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ActorMigrationRecoveryTest extends TestCase
{
    private string $previousConnection;
    protected function setUp(): void
    {
        parent::setUp();
        $this->previousConnection=DB::getDefaultConnection();
        config(['database.connections.actor_recovery'=>['driver'=>'sqlite','database'=>':memory:','prefix'=>'','foreign_key_constraints'=>true]]);
        DB::setDefaultConnection('actor_recovery');
        foreach(['users','entities','systems','cohesions'] as $name) { Schema::create($name,fn(Blueprint $t)=>$t->id()); }
        Schema::create('identities',function(Blueprint $t) { $t->id(); $t->foreignId('user_id')->constrained(); $t->foreignId('entity_id')->constrained(); $t->string('identification')->nullable(); });
        Schema::create('system_user',function(Blueprint $t) { $t->id(); $t->foreignId('system_id')->constrained(); $t->foreignId('user_id')->constrained(); $t->timestamps(); });
        Schema::create('actors',function(Blueprint $t) { $t->id(); $t->foreignId('identity_id')->constrained(); $t->unique('identity_id'); $t->timestamps(); });
        Schema::create('units',function(Blueprint $t) { $t->id(); foreach(['name','symbol','system','magnitude','type'] as $column) { $t->string($column)->nullable(); } $t->timestamps(); });
        DB::table('users')->insert(['id'=>1]); DB::table('entities')->insert(['id'=>1]);
        DB::table('identities')->insert(['id'=>1,'user_id'=>1,'entity_id'=>1,'identification'=>'JFRV']);
        DB::table('actors')->insert(['id'=>1,'identity_id'=>1]);
    }
    protected function tearDown(): void
    {
        DB::purge('actor_recovery'); DB::setDefaultConnection($this->previousConnection);
        parent::tearDown();
    }
    private function migration()
    {
        return require database_path('migrations/2026_10_13_190000_expand_system_actors.php');
    }
    public function test_recovers_after_identity_columns_and_membership_column_were_created(): void
    {
        Schema::table('identities',function(Blueprint $t) {
            foreach(['person_type','first_name','second_name','third_name','paternal_surname','maternal_surname','legal_name','nationality','identifier_country','identifier_kind','identifier_number','email','phone','address','city','region','country'] as $column) { $t->string($column)->nullable(); }
            $t->date('birth_date')->nullable();
            $t->unique(['identifier_country','identifier_kind','identifier_number'],'identities_formal_identifier_unique');
        });
        Schema::table('system_user',fn(Blueprint $t)=>$t->string('application_status')->nullable());
        DB::table('identities')->where('id',1)->update(['first_name'=>'José']);
        $this->migration()->up();
        $this->assertTrue(Schema::hasIndex('actors','actors_identity_id_index'));
        $this->assertFalse(Schema::hasIndex('actors','actors_identity_id_unique'));
        $this->assertDatabaseHas('identities',['id'=>1,'first_name'=>'José','identification'=>'JFRV']);
        $this->assertDatabaseHas('actors',['id'=>1,'identity_id'=>1,'system_id'=>null]);
        DB::table('actors')->insert(['id'=>2,'identity_id'=>1]);
        $this->assertDatabaseCount('actors',2);
        $this->assertCount(5,Schema::getForeignKeys('actors'));
    }
    public function test_fresh_install_and_completed_retry_do_not_duplicate_schema_or_records(): void
    {
        $this->migration()->up();
        $this->migration()->up();
        $this->assertDatabaseCount('actors',1);
        $this->assertDatabaseCount('identities',1);
        $this->assertDatabaseCount('units',5);
        $this->assertCount(5,Schema::getForeignKeys('actors'));
    }
    public function test_recovers_missing_foreign_keys_after_a_column_was_created(): void
    {
        Schema::table('actors',fn(Blueprint $t)=>$t->foreignId('system_id')->nullable());
        $this->migration()->up();
        $this->assertTrue(collect(Schema::getForeignKeys('actors'))->contains(fn($key)=>$key['columns']===['system_id']));
        $this->assertDatabaseHas('actors',['id'=>1,'identity_id'=>1]);
    }
}
