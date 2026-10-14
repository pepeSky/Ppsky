<?php
namespace Tests\Feature;

use App\Models\{Actor,Cohesion,Entity,Identity,Nature,System,SystemRole,User};
use App\Services\MeWo\ActorAccess;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemActorsTest extends TestCase
{
    use RefreshDatabase;
    private User $owner;
    private System $system;
    private Identity $identity;
    protected function setUp(): void
    {
        parent::setUp();
        $this->owner=User::factory()->create();
        $nature=Nature::create(['name'=>'Persona','slug'=>'persona']);
        $entity=Entity::create(['name'=>'JFRV','code'=>'JFRV','nature_id'=>$nature->id]);
        $this->identity=Identity::create(['user_id'=>$this->owner->id,'entity_id'=>$entity->id]);
        $this->system=System::create(['identity_id'=>$this->identity->id]);
        $this->system->users()->attach($this->owner);
        foreach(['0:0'=>'Existencial','0:1'=>'Ocio','1:1'=>'Negocio'] as $symbol=>$name) { Cohesion::create(compact('symbol','name')); }
        $this->actingAs($this->owner)->withSession(['active_system_id'=>$this->system->id]);
    }
    private function register(string $unit='UnSe', int $type=1, array $extra=[]): Actor
    {
        $response=$this->post(route('admin.actors.store'),$extra+['identity_id'=>$this->identity->id,'unit_symbol'=>$unit,'type_position'=>$type]);
        $response->assertSessionHasNoErrors()->assertRedirect();
        return Actor::latest('id')->firstOrFail();
    }
    private function anotherSystem(): System
    {
        $entity=Entity::create(['name'=>'PepeSky','code'=>'PPSK','nature_id'=>$this->identity->entity->nature_id]);
        $identity=Identity::create(['user_id'=>$this->owner->id,'entity_id'=>$entity->id]);
        $system=System::create(['identity_id'=>$identity->id]); $system->users()->attach($this->owner);
        return $system;
    }
    public function test_identity_supports_several_actor_classifications_and_local_sequences(): void
    {
        $a=$this->register(); $b=$this->register(); $c=$this->register('UnOp',6);
        $this->assertSame('UnSe 1.001',$a->code); $this->assertSame('UnSe 1.002',$b->code); $this->assertSame('UnOp 6.001',$c->code);
        $other=$this->anotherSystem(); $this->withSession(['active_system_id'=>$other->id]);
        $d=$this->register(); $this->assertSame('UnSe 1.001',$d->code);
        $this->assertSame(4,$this->identity->actors()->count());
    }
    public function test_invalid_type_is_rejected_without_consuming_sequence(): void
    {
        $this->post(route('admin.actors.store'),['identity_id'=>$this->identity->id,'unit_symbol'=>'UnOp','type_position'=>99])->assertSessionHasErrors('type_position');
        $this->assertDatabaseCount('actors',0); $this->assertSame('UnOp 6.001',$this->register('UnOp',6)->code);
    }
    public function test_existing_classification_cannot_be_reassigned(): void
    {
        $actor=$this->register();
        $this->put(route('admin.actors.update',$actor),['unit_symbol'=>'UnEl','type_position'=>1])->assertSessionHasErrors('unit_symbol');
        $this->assertSame('UnSe 1.001',$actor->fresh()->code);
    }
    public function test_multiple_system_interactions_are_preserved(): void
    {
        $ids=Cohesion::pluck('id')->all(); $actor=$this->register(extra:['cohesion_ids'=>$ids]);
        $this->assertSame(3,$actor->cohesions()->count());
        $this->put(route('admin.actors.update',$actor),['cohesions_present'=>1])->assertRedirect();
        $this->assertSame(0,$actor->cohesions()->count());
    }
    public function test_many_to_many_unit_links_and_direct_links(): void
    {
        $n1=$this->register('UnNE',3); $n2=$this->register('UnNE',3); $s=$this->register(); $e=$this->register('UnEl',6);
        foreach([$n1,$n2] as $parent) { $this->put(route('admin.actors.links',$parent),['child_ids'=>[$s->id,$e->id]])->assertSessionHasNoErrors()->assertRedirect(); }
        $this->assertSame(2,$s->parents()->count()); $this->assertSame(2,$e->parents()->count());
        $this->get(route('admin.actors.show',$n1))->assertOk()->assertSee($e->code);
    }
    public function test_inverted_and_cross_system_links_are_rejected(): void
    {
        $n=$this->register('UnNE',3); $e=$this->register('UnEl',6);
        $this->put(route('admin.actors.links',$e),['child_ids'=>[$n->id]])->assertSessionHasErrors('child_ids');
        $other=$this->anotherSystem(); $this->withSession(['active_system_id'=>$other->id]); $foreign=$this->register();
        $this->withSession(['active_system_id'=>$this->system->id]);
        $this->put(route('admin.actors.links',$n),['child_ids'=>[$foreign->id]])->assertSessionHasErrors('child_ids');
    }
    public function test_actor_routes_do_not_leak_other_systems(): void
    {
        $other=$this->anotherSystem(); $this->withSession(['active_system_id'=>$other->id]); $foreign=$this->register();
        $this->withSession(['active_system_id'=>$this->system->id]);
        $this->get(route('admin.actors.show',$foreign))->assertNotFound();
        $this->put(route('admin.actors.update',$foreign),[])->assertNotFound();
        $this->delete(route('admin.actors.destroy',$foreign))->assertForbidden();
        $this->get(route('admin.actors.index'))->assertOk()->assertDontSee('UnSe 1.001');
    }
    public function test_external_reference_shares_identity_and_not_authorizations(): void
    {
        $other=$this->anotherSystem(); $this->withSession(['active_system_id'=>$other->id]);
        $origin=$this->register(extra:['participation_status'=>'authorized']);
        $this->withSession(['active_system_id'=>$this->system->id]);
        $this->post(route('admin.actors.store'),['origin_system_id'=>$other->id,'origin_code'=>$origin->code,'unit_symbol'=>'UnNE','type_position'=>3])->assertRedirect();
        $local=Actor::latest('id')->firstOrFail();
        $this->assertSame($origin->id,$local->origin_actor_id); $this->assertSame($origin->identity_id,$local->identity_id);
        $this->assertSame('pending',$local->participation_status); $this->assertSame('pending',$local->application_status);
        $this->get(route('admin.actors.show',$local))->assertOk()->assertSee('Actor de origen');
    }
    public function test_invalid_external_reference_is_not_created(): void
    {
        $this->post(route('admin.actors.store'),['origin_system_id'=>100,'origin_code'=>'UnSe 1.001'])->assertSessionHasErrors('origin_code');
        $this->assertDatabaseCount('actors',0);
    }
    public function test_roles_are_delegated_within_one_system_only(): void
    {
        $viewer=User::factory()->create(); $this->system->users()->attach($viewer);
        $this->post(route('admin.actor-access.store'),['name'=>'Consulta','permissions'=>['actors.view']])->assertRedirect();
        $role=SystemRole::firstOrFail();
        $this->put(route('admin.actor-access.update',$role),['permissions'=>['actors.view'],'user_ids'=>[$viewer->id]])->assertRedirect();
        $actor=$this->register(); $other=$this->anotherSystem(); $other->users()->attach($viewer);
        $this->actingAs($viewer)->withSession(['active_system_id'=>$this->system->id]);
        $this->get(route('admin.actors.index'))->assertOk();
        $this->get(route('admin.actors.show',$actor))->assertOk()->assertDontSee('Datos formales de identidad');
        $this->post(route('admin.actors.store'),['identity_id'=>$this->identity->id])->assertForbidden();
        $this->get(route('admin.actor-access.index'))->assertForbidden();
        $this->withSession(['active_system_id'=>$other->id]); $this->get(route('admin.actors.index'))->assertForbidden();
    }
    public function test_foreign_members_roles_and_unknown_permissions_are_rejected(): void
    {
        $stranger=User::factory()->create();
        $this->post(route('admin.actor-access.store'),['name'=>'Incorrecto','permissions'=>['*']])->assertSessionHasErrors('permissions.0');
        $this->post(route('admin.actor-access.store'),['name'=>'Consulta','permissions'=>['actors.view']])->assertRedirect(); $role=SystemRole::firstOrFail();
        $this->put(route('admin.actor-access.update',$role),['user_ids'=>[$stranger->id]])->assertSessionHasErrors('user_ids');
        $other=$this->anotherSystem(); $this->withSession(['active_system_id'=>$other->id]);
        $this->put(route('admin.actor-access.update',$role),[])->assertNotFound();
    }
    public function test_member_cannot_bypass_specific_authorization_permissions(): void
    {
        $user=User::factory()->create(); $this->system->users()->attach($user);
        $role=SystemRole::create(['system_id'=>$this->system->id,'name'=>'Registro','permissions'=>['actors.view','actors.edit']]); $role->users()->attach($user);
        $actor=$this->register(); $this->actingAs($user);
        $this->put(route('admin.actors.update',$actor),['application_status'=>'authorized','account_user_id'=>$user->id])->assertForbidden();
        $this->put(route('admin.actors.update',$actor),['participation_status'=>'authorized'])->assertForbidden();
        $this->assertSame('pending',$actor->fresh()->participation_status);
    }
    public function test_application_access_is_separate_and_local_account_is_required(): void
    {
        $user=User::factory()->create(); $this->system->users()->attach($user);
        $actor=$this->register(extra:['participation_status'=>'authorized']);
        $this->assertSame('pending',$actor->application_status);
        $this->put(route('admin.actors.update',$actor),['application_status'=>'authorized'])->assertSessionHasErrors('account_user_id');
        $this->put(route('admin.actors.update',$actor),['application_status'=>'authorized','account_user_id'=>$user->id])->assertRedirect();
        $role=SystemRole::create(['system_id'=>$this->system->id,'name'=>'Consulta','permissions'=>['actors.view']]); $role->users()->attach($user);
        $this->actingAs($user)->get(route('admin.actors.index'))->assertOk();
        $this->actingAs($this->owner)->put(route('admin.actors.update',$actor),['application_status'=>'blocked'])->assertRedirect();
        $this->actingAs($user)->get(route('admin.actors.index'))->assertForbidden();
    }
    public function test_frontend_deletion_is_blocked_and_preserves_code_identity_and_links(): void
    {
        $n=$this->register('UnNE',3); $s=$this->register();
        $this->put(route('admin.actors.links',$n),['child_ids'=>[$s->id]])->assertRedirect();
        $this->delete(route('admin.actors.destroy',$s))->assertForbidden();
        $this->assertNull($s->fresh()->archived_at); $this->assertSame(1,$n->children()->count());
        $this->assertSame('UnSe 1.002',$this->register()->code);
        $this->delete(route('admin.identities.destroy',$this->identity))->assertForbidden();
    }
    public function test_legacy_actors_are_not_automatically_assigned_or_deleted(): void
    {
        $actor=Actor::create(['identity_id'=>$this->identity->id]);
        $this->get(route('admin.actors.index'))->assertOk()->assertSee('Sistema pendiente de asignación');
        $this->assertNull($actor->fresh()->system_id);
        $this->put(route('admin.actors.update',$actor),['unit_symbol'=>'UnNE','type_position'=>3])->assertRedirect();
        $this->assertSame($this->system->id,$actor->fresh()->system_id); $this->assertSame('UnNE 3.001',$actor->fresh()->code);
    }
    public function test_formal_identity_fields_and_normalized_document_uniqueness(): void
    {
        $data=['person_type'=>'natural','identifier_country'=>'cl','identifier_kind'=>'rut','identifier_number'=>'12.345.678-5',
            'first_name'=>'José','second_name'=>'Fernando','paternal_surname'=>'Riquelme','maternal_surname'=>'Vergara','email'=>'pepe@example.com','nationality'=>'Chilena'];
        $this->put(route('admin.identities.update',$this->identity),$data)->assertRedirect();
        $this->assertSame('123456785',$this->identity->fresh()->identifier_number);
        $other=$this->anotherSystem()->identity;
        $this->put(route('admin.identities.update',$other),$data)->assertSessionHasErrors('identifier_number');
        $this->get(route('admin.identities.show',$this->identity))->assertOk()->assertSee('Fernando');
    }
    public function test_forms_and_access_management_render(): void
    {
        $this->get(route('admin.actors.create'))->assertOk()->assertSee('Tipificación');
        $a=$this->register(); $this->get(route('admin.actors.edit',$a))->assertOk();
        $this->get(route('admin.actor-access.index'))->assertOk();
        $this->get(route('admin.identities.create'))->assertOk();
        $this->get(route('admin.identities.edit',$this->identity))->assertOk();
    }
    public function test_all_permitted_unit_links_and_multiple_operational_parents(): void
    {
        $units=[];
        foreach(array_keys(config('actors.types')) as $symbol) { $units[$symbol]=$this->register($symbol,1); }
        foreach(config('actors.links') as $symbol=>$targets) {
            $ids=array_map(fn($target)=>$units[$target]->id,$targets);
            $this->put(route('admin.actors.links',$units[$symbol]),['child_ids'=>$ids])->assertSessionHasNoErrors()->assertRedirect();
            $this->assertSame(count($ids),$units[$symbol]->children()->count());
        }
        $this->assertSame(4,$units['UnEl']->parents()->count());
    }
    public function test_sequence_grows_beyond_three_digits_and_is_audited(): void
    {
        $this->register();
        \Illuminate\Support\Facades\DB::table('actor_sequences')->where('system_id',$this->system->id)->where('unit_symbol','UnSe')->update(['last_number'=>999]);
        $actor=$this->register(); $this->assertSame('UnSe 1.1000',$actor->code);
        $this->assertDatabaseHas('actor_changes',['actor_id'=>$actor->id,'system_id'=>$this->system->id,'user_id'=>$this->owner->id,'action'=>'registered']);
        $this->assertNotNull($actor->unit_id); $this->assertSame('SyQuAc',$actor->unit->system);
    }
    public function test_foreign_identity_details_and_private_identifier_are_not_exposed(): void
    {
        $foreignUser=User::factory()->create();
        $entity=Entity::create(['name'=>'Actor externo','code'=>'EXT','nature_id'=>$this->identity->entity->nature_id]);
        $identity=Identity::create(['user_id'=>$foreignUser->id,'entity_id'=>$entity->id,'email'=>'privado@example.com','identifier_number'=>'987654321']);
        $foreignSystem=System::create(['identity_id'=>$identity->id]); $foreignSystem->users()->attach($foreignUser);
        $origin=Actor::create(['identity_id'=>$identity->id,'system_id'=>$foreignSystem->id,'unit_symbol'=>'UnSe','type_position'=>5,'code'=>'UnSe 5.001']);
        $this->post(route('admin.actors.store'),['identity_id'=>$identity->id])->assertNotFound();
        $this->post(route('admin.actors.store'),['origin_system_id'=>$foreignSystem->id,'origin_code'=>$origin->code,'unit_symbol'=>'UnSe','type_position'=>5])->assertRedirect();
        $local=Actor::latest('id')->firstOrFail();
        $this->get(route('admin.actors.show',$local))->assertOk()->assertDontSee('privado@example.com')->assertDontSee('987654321');
        $this->get(route('admin.identities.show',$identity))->assertForbidden();
        $this->put(route('admin.identities.update',$identity),['email'=>'otro@example.com'])->assertForbidden();
        $this->get(route('admin.actors.show',$origin))->assertNotFound();
    }
    public function test_pair_interactions_use_only_authorized_actors_in_the_active_system(): void
    {
        $a=$this->register(extra:['participation_status'=>'authorized']); $b=$this->register('UnOp',6,['participation_status'=>'authorized']);
        $cohesion=Cohesion::where('symbol','1:1')->firstOrFail();
        $payload=['actor_a_id'=>$a->id,'actor_b_id'=>$b->id,'cohesion_id'=>$cohesion->id];
        $this->post(route('admin.interactions.store'),$payload)->assertRedirect();
        $this->post(route('admin.interactions.store'),$payload)->assertRedirect();
        $interaction=\App\Models\Interaction::firstOrFail(); $this->assertDatabaseCount('interactions',1);
        $this->get(route('admin.interactions.index'))->assertOk(); $this->get(route('admin.interactions.show',$interaction))->assertOk();
        $pending=$this->register('UnEl',6); $payload['actor_b_id']=$pending->id;
        $this->post(route('admin.interactions.store'),$payload)->assertNotFound();
        $other=$this->anotherSystem(); $this->withSession(['active_system_id'=>$other->id]);
        $this->get(route('admin.interactions.show',$interaction))->assertNotFound();
        $this->get(route('admin.posts.index'))->assertOk()->assertDontSee('JFRV ↔');
    }
    public function test_blocked_account_cannot_use_application_without_active_session(): void
    {
        $user=User::factory()->create(); $this->system->users()->attach($user);
        $this->register(extra:['account_user_id'=>$user->id,'application_status'=>'blocked']);
        $this->actingAs($user)->withSession(['active_system_id'=>null]);
        $this->get(route('admin.posts.index'))->assertForbidden();
        $this->post(route('admin.system.active'),['system_id'=>$this->system->id])->assertForbidden();
    }
    public function test_account_can_be_unlinked_without_changing_participation(): void
    {
        $user=User::factory()->create(); $this->system->users()->attach($user);
        $actor=$this->register(extra:['account_user_id'=>$user->id,'application_status'=>'authorized','participation_status'=>'authorized']);
        $this->put(route('admin.actors.update',$actor),['account_user_id'=>null,'application_status'=>'pending'])->assertRedirect();
        $this->assertNull($actor->fresh()->account_user_id); $this->assertSame('authorized',$actor->fresh()->participation_status);
        $this->actingAs($user)->get(route('admin.posts.index'))->assertForbidden();
    }
    public function test_syquac_catalogue_cannot_be_deleted_or_renamed_after_use(): void
    {
        $actor=$this->register();
        $this->delete(route('admin.units.destroy',$actor->unit))->assertForbidden();
        $this->put(route('admin.units.update',$actor->unit),['name'=>'Servicio','symbol'=>'Otra','system'=>'SyQuAc'])->assertSessionHasErrors('symbol');
        $this->assertSame('UnSe',$actor->unit->fresh()->symbol);
    }
    public function test_malformed_formal_identifier_returns_validation_error(): void
    {
        $this->put(route('admin.identities.update',$this->identity),['identifier_number'=>['invalid'],'identifier_country'=>'CL','identifier_kind'=>'RUT'])->assertSessionHasErrors('identifier_number');
    }
    public function test_shared_identity_cannot_be_duplicated_or_erased_through_entity_routes(): void
    {
        $this->post(route('admin.identities.store'),['entity_id'=>$this->identity->entity_id])->assertSessionHasErrors('entity_id');
        $this->delete(route('admin.entities.destroy',$this->identity->entity))->assertForbidden();
        $this->assertDatabaseHas('identities',['id'=>$this->identity->id]);
        $other=User::factory()->create(); $this->system->users()->attach($other);
        $role=SystemRole::create(['system_id'=>$this->system->id,'name'=>'Identidades','permissions'=>['identities.manage']]); $role->users()->attach($other);
        $this->actingAs($other)->put(route('admin.entities.update',$this->identity->entity),['name'=>'Cambiar dueño','nature_id'=>$this->identity->entity->nature_id])->assertForbidden();
    }
}
