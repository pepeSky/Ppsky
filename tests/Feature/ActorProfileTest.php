<?php
namespace Tests\Feature;
use App\Models\{Actor,ActorClassification,Cohesion,Entity,Identity,Nature,System,SystemRole,Unit,User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActorProfileTest extends TestCase
{
    use RefreshDatabase;
    private User $owner;
    private System $system;
    private Identity $identity;
    private Nature $nature;
    protected function setUp(): void
    {
        parent::setUp();
        $this->owner=User::factory()->create(); $this->nature=Nature::create(['name'=>'Humano','slug'=>'humano']);
        $entity=Entity::create(['name'=>'José Fernando Riquelme Vergara','code'=>'JFRV','nature_id'=>$this->nature->id]);
        $this->identity=Identity::create(['user_id'=>$this->owner->id,'entity_id'=>$entity->id,'person_type'=>'natural','first_name'=>'José','second_name'=>'Fernando','third_name'=>'Tercero','paternal_surname'=>'Riquelme','maternal_surname'=>'Vergara','email'=>'pepe@example.com']);
        $this->system=System::create(['identity_id'=>$this->identity->id]); $this->system->users()->attach($this->owner);
        foreach(['0:0'=>'Existencial','0:1'=>'Ocio','1:1'=>'Negocio'] as $symbol=>$name) { Cohesion::create(compact('symbol','name')); }
        $this->actingAs($this->owner)->withSession(['active_system_id'=>$this->system->id]);
    }
    private function actor(): Actor
    {
        $this->post(route('admin.actors.store'),['name'=>$this->identity->display_name,'identity_id'=>$this->identity->id,'cohesions_present'=>1,'cohesion_ids'=>Cohesion::pluck('id')->all()])->assertSessionHasNoErrors()->assertRedirect();
        return Actor::latest('id')->firstOrFail();
    }
    public function test_name_then_cohesions_and_separate_import_button_without_system_selector(): void
    {
        $this->get(route('admin.actors.create'))->assertOk()->assertSeeInOrder(['Registrar actor','Sistema: José Fernando Riquelme Vergara','Nombre del actor','Cohesiones con el sistema'])->assertDontSee('origin_system_id')->assertDontSee('Registrar referencia');
        $this->get(route('admin.actors.index'))->assertOk()->assertSee('Importar actor')->assertSee('Nombre del actor')->assertDontSee('actors_system')->assertDontSee('name="system_id"',false);
        $this->get(route('admin.actors.import'))->assertOk()->assertSee('Importar actor')->assertSee('origin_system_id')->assertSee('Sistema: José Fernando Riquelme Vergara');
    }
    public function test_actor_name_and_identity_name_are_separate_and_composed_as_requested(): void
    {
        $actor=$this->actor(); $this->assertSame('José Fernando Riquelme Vergara',$actor->display_name);
        $this->assertSame('José Fernando Riquelme Vergara',$this->identity->display_name);
        $this->put(route('admin.actors.update',$actor),['name'=>'JFRV como trabajador'])->assertRedirect();
        $this->assertSame('JFRV como trabajador',$actor->fresh()->name);
        $this->assertSame('José Fernando Riquelme Vergara',$this->identity->fresh()->display_name);
        $this->get(route('admin.actors.show',$actor))->assertOk()->assertSee('Ficha del actor: JFRV como trabajador')->assertSee('Fernando')->assertSee('Riquelme')->assertSee('Vergara')->assertSee('pepe@example.com')->assertDontSee('>Archivar</button>',false);
    }
    public function test_name_only_reuses_existing_identity(): void
    {
        $this->post(route('admin.actors.store'),['name'=>$this->identity->display_name,'cohesions_present'=>1,'cohesion_ids'=>Cohesion::pluck('id')->all()])->assertRedirect();
        $this->assertDatabaseCount('identities',1); $this->assertSame($this->identity->id,Actor::firstOrFail()->identity_id);
    }
    public function test_new_actor_can_register_identity_and_formal_details_in_one_transaction(): void
    {
        $this->post(route('admin.actors.store'),['name'=>'Juan Pedro Pérez Soto','nature_id'=>$this->nature->id,'person_type'=>'natural','first_name'=>'Juan','second_name'=>'Pedro','paternal_surname'=>'Pérez','maternal_surname'=>'Soto','email'=>'juan@example.com','cohesions_present'=>1,'cohesion_ids'=>[Cohesion::where('symbol','0:1')->first()->id]])->assertSessionHasNoErrors()->assertRedirect();
        $actor=Actor::firstOrFail(); $this->assertSame('Juan Pedro Pérez Soto',$actor->display_name); $this->assertSame('juan@example.com',$actor->identity->email);
        $this->get(route('admin.actors.show',$actor))->assertOk()->assertSee('Pérez')->assertSee('Soto');
    }
    public function test_missing_cohesion_rolls_back_inline_identity_and_actor(): void
    {
        $this->post(route('admin.actors.store'),['name'=>'Actor pendiente','nature_id'=>$this->nature->id,'cohesions_present'=>1])->assertSessionHasErrors('cohesion_ids');
        $this->assertDatabaseCount('actors',0); $this->assertDatabaseCount('identities',1); $this->assertDatabaseCount('entities',1);
    }
    public function test_one_actor_can_have_five_classifications_without_duplicating_name(): void
    {
        $actor=$this->actor(); $business=Cohesion::where('symbol','1:1')->firstOrFail();
        foreach(['UnNE'=>3,'UnSe'=>5,'UnSo'=>6,'UnOp'=>7,'UnEl'=>9] as $symbol=>$type) {
            $this->post(route('admin.actors.classifications.store',$actor),['unit_symbol'=>$symbol,'type_position'=>$type,'cohesion_id'=>$business->id])->assertSessionHasNoErrors()->assertRedirect();
        }
        $this->assertDatabaseCount('actors',1); $this->assertSame(5,$actor->classifications()->count());
        $this->get(route('admin.actors.show',$actor))->assertOk()->assertSee('UnNE 3.001')->assertSee('UnSe 5.001')->assertSee('UnSo 6.001')->assertSee('UnOp 7.001')->assertSee('UnEl 9.001')->assertSee('Padre');
    }
    public function test_repeated_classification_preserves_code_and_uses_shared_sequence(): void
    {
        $actor=$this->actor(); $payload=['unit_symbol'=>'UnSe','type_position'=>5];
        $this->post(route('admin.actors.classifications.store',$actor),$payload)->assertRedirect();
        $this->post(route('admin.actors.classifications.store',$actor),$payload)->assertRedirect();
        $this->assertSame(1,$actor->classifications()->count());
        $this->post(route('admin.actors.store'),['identity_id'=>$this->identity->id,'unit_symbol'=>'UnSe','type_position'=>5])->assertRedirect();
        $this->assertSame('UnSe 5.002',Actor::latest('id')->firstOrFail()->code);
    }
    public function test_classification_cannot_invent_actor_cohesion(): void
    {
        $actor=$this->actor(); $actor->cohesions()->sync([Cohesion::where('symbol','0:1')->first()->id]);
        $this->post(route('admin.actors.classifications.store',$actor),['unit_symbol'=>'UnNE','type_position'=>3,'cohesion_id'=>Cohesion::where('symbol','1:1')->first()->id])->assertSessionHasErrors('cohesion_id');
        $this->assertSame(0,$actor->classifications()->count());
    }
    public function test_classification_links_can_join_different_roles_of_the_same_actor(): void
    {
        $actor=$this->actor();
        foreach(['UnNE'=>3,'UnOp'=>7,'UnEl'=>9] as $symbol=>$type) { $this->post(route('admin.actors.classifications.store',$actor),['unit_symbol'=>$symbol,'type_position'=>$type])->assertRedirect(); }
        $ne=$actor->classifications()->where('unit_symbol','UnNE')->first(); $op=$actor->classifications()->where('unit_symbol','UnOp')->first(); $el=$actor->classifications()->where('unit_symbol','UnEl')->first();
        $this->put(route('admin.actors.classifications.links',$ne),['child_ids'=>[$op->id,$el->id]])->assertRedirect();
        $this->put(route('admin.actors.classifications.links',$op),['child_ids'=>[$el->id]])->assertRedirect();
        $this->assertSame(2,$el->parents()->count());
        $this->put(route('admin.actors.classifications.links',$el),['child_ids'=>[$ne->id]])->assertSessionHasErrors('child_ids');
    }
    public function test_import_accepts_secondary_code_and_defines_local_cohesions(): void
    {
        $origin=$this->actor(); $this->post(route('admin.actors.classifications.store',$origin),['unit_symbol'=>'UnSe','type_position'=>5])->assertRedirect();
        $entity=Entity::create(['name'=>'PepeSky','code'=>'PPSK','nature_id'=>$this->nature->id]); $identity=Identity::create(['user_id'=>$this->owner->id,'entity_id'=>$entity->id]);
        $other=System::create(['identity_id'=>$identity->id]); $other->users()->attach($this->owner); $this->withSession(['active_system_id'=>$other->id]);
        $this->post(route('admin.actors.import.store'),['origin_system_id'=>$this->system->id,'origin_code'=>'UnSe 5.001','cohesions_present'=>1,'cohesion_ids'=>[Cohesion::where('symbol','0:1')->first()->id]])->assertRedirect();
        $local=Actor::latest('id')->first(); $this->assertSame($origin->id,$local->origin_actor_id); $this->assertSame($this->identity->id,$local->identity_id); $this->assertSame(1,$local->cohesions()->count()); $this->assertSame(0,$local->classifications()->count());
    }
    public function test_name_creation_requires_identity_management_permission(): void
    {
        $member=User::factory()->create(); $this->system->users()->attach($member); $role=SystemRole::create(['system_id'=>$this->system->id,'name'=>'Registro','permissions'=>['actors.create','actors.interactions']]); $role->users()->attach($member);
        $this->actingAs($member)->post(route('admin.actors.store'),['name'=>'Nueva identidad','nature_id'=>$this->nature->id])->assertForbidden();
        $this->assertDatabaseCount('identities',1);
    }
    public function test_legal_identity_uses_legal_name(): void
    {
        $this->identity->update(['person_type'=>'legal','legal_name'=>'Fundación Riquelme & Cía.']);
        $this->assertSame('Fundación Riquelme & Cía.',$this->identity->fresh()->display_name);
    }
    public function test_migration_preserves_old_codes_and_later_cohesion_associations(): void
    {
        $actor=$this->actor(); $this->post(route('admin.actors.classifications.store',$actor),['unit_symbol'=>'UnNE','type_position'=>3,'cohesion_id'=>Cohesion::where('symbol','1:1')->first()->id])->assertRedirect();
        $code=$actor->fresh()->code; $cohesion=$actor->classifications()->first()->cohesion_id;
        (require database_path('migrations/2026_10_13_220000_add_actor_names_and_classifications.php'))->up();
        $this->assertSame($code,$actor->fresh()->code); $this->assertSame($cohesion,$actor->classifications()->first()->cohesion_id);
        $this->assertSame(1,$actor->classifications()->count());
    }
    public function test_new_classification_routes_preserve_system_isolation(): void
    {
        $local=$this->actor(); $this->post(route('admin.actors.classifications.store',$local),['unit_symbol'=>'UnNE','type_position'=>3])->assertRedirect();
        $parent=$local->classifications()->first();
        $entity=Entity::create(['name'=>'Otro sistema','code'=>'OTR','nature_id'=>$this->nature->id]); $identity=Identity::create(['user_id'=>$this->owner->id,'entity_id'=>$entity->id]);
        $other=System::create(['identity_id'=>$identity->id]); $other->users()->attach($this->owner); $this->withSession(['active_system_id'=>$other->id]);
        $foreign=$this->actor(); $this->post(route('admin.actors.classifications.store',$foreign),['unit_symbol'=>'UnEl','type_position'=>9])->assertRedirect();
        $child=$foreign->classifications()->first();
        $this->withSession(['active_system_id'=>$this->system->id]);
        $this->put(route('admin.actors.classifications.links',$parent),['child_ids'=>[$child->id]])->assertSessionHasErrors('child_ids');
        $this->put(route('admin.actors.classifications.links',$child),[])->assertNotFound();
        $this->post(route('admin.actors.classifications.store',$foreign),['unit_symbol'=>'UnSe','type_position'=>5])->assertNotFound();
        $member=User::factory()->create(); $this->system->users()->attach($member);
        $role=SystemRole::create(['system_id'=>$this->system->id,'name'=>'Consulta','permissions'=>['actors.view']]); $role->users()->attach($member);
        $this->actingAs($member)->post(route('admin.actors.classifications.store',$local),['unit_symbol'=>'UnSe','type_position'=>5])->assertForbidden();
    }
    public function test_removing_cohesion_does_not_remove_classification_code(): void
    {
        $actor=$this->actor(); $business=Cohesion::where('symbol','1:1')->first();
        $this->post(route('admin.actors.classifications.store',$actor),['unit_symbol'=>'UnNE','type_position'=>3,'cohesion_id'=>$business->id])->assertRedirect();
        $this->put(route('admin.actors.update',$actor),['cohesions_present'=>1,'cohesion_ids'=>[Cohesion::where('symbol','0:1')->first()->id]])->assertRedirect();
        $classification=$actor->classifications()->first(); $this->assertSame('UnNE 3.001',$classification->code); $this->assertNull($classification->cohesion_id);
    }
    public function test_existing_actor_code_is_backfilled_without_renumbering(): void
    {
        $actor=$this->actor(); $unit=Unit::where('symbol','UnNE')->first();
        $actor->update(['name'=>null,'unit_symbol'=>'UnNE','unit_id'=>$unit->id,'type_position'=>3,'sequence'=>9,'code'=>'UnNE 3.009']);
        (require database_path('migrations/2026_10_13_220000_add_actor_names_and_classifications.php'))->up();
        $this->assertSame('UnNE 3.009',$actor->classifications()->first()->code);
        $this->assertSame('José Fernando Riquelme Vergara',$actor->fresh()->display_name);
        $this->post(route('admin.actors.store'),['identity_id'=>$this->identity->id,'unit_symbol'=>'UnNE','type_position'=>3])->assertRedirect();
        $this->assertSame('UnNE 3.010',Actor::latest('id')->first()->code);
    }
    public function test_composed_actor_name_tracks_identity_details_without_overwriting_explicit_name(): void
    {
        $actor=$this->actor(); $this->assertNull($actor->name);
        $this->identity->update(['second_name'=>'Andrés']);
        $this->assertSame('José Andrés Riquelme Vergara',$actor->fresh()->display_name);
        $this->put(route('admin.actors.update',$actor),['name'=>'JFRV como padre'])->assertRedirect();
        $this->identity->update(['second_name'=>'Fernando']);
        $this->assertSame('JFRV como padre',$actor->fresh()->display_name);
    }
    public function test_distinct_people_with_same_name_are_not_merged_by_name(): void
    {
        $this->post(route('admin.actors.store'),['name'=>$this->identity->display_name,'new_identity'=>1,'nature_id'=>$this->nature->id,'identifier_country'=>'CL','identifier_kind'=>'RUT','identifier_number'=>'12.345.678-5','cohesions_present'=>1,'cohesion_ids'=>[Cohesion::first()->id]])->assertSessionHasNoErrors()->assertRedirect();
        $actor=Actor::firstOrFail(); $this->assertNotSame($this->identity->id,$actor->identity_id);
        $this->assertSame('123456785',$actor->identity->identifier_number);
        $this->assertDatabaseCount('identities',2);
    }
}
