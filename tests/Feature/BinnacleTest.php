<?php

namespace Tests\Feature;

use App\Models\Actor;
use App\Models\Binnacle;
use App\Models\Entity;
use App\Models\Identity;
use App\Models\Nature;
use App\Models\Project;
use App\Models\System;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BinnacleTest extends TestCase
{
    use RefreshDatabase;
    private User $owner;
    private System $system;
    private Actor $actor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->owner = User::factory()->create();
        $nature = Nature::create(['name'=>'Persona','slug'=>'persona']);
        $entity = Entity::create(['name'=>$this->owner->name,'code'=>'JFRV','nature_id'=>$nature->id]);
        $identity = Identity::create(['user_id'=>$this->owner->id,'entity_id'=>$entity->id]);
        $this->system = System::create(['identity_id'=>$identity->id]);
        $this->system->users()->attach($this->owner);
        $this->actor = Actor::create(['identity_id'=>$identity->id]);
        $this->actingAs($this->owner);
    }

    private function record(string $title='Bitácora de trabajo', string $date='2026-10-13'): Binnacle
    {
        return Binnacle::create(['system_id'=>$this->system->id,'user_id'=>$this->owner->id,'recorded_on'=>$date,'title'=>$title]);
    }

    public function test_history_has_requested_buttons_and_no_add_posts(): void
    {
        $this->get(route('admin.posts.index'))->assertOk()->assertSee('Bitácoras del Sistema')
            ->assertSee('Agregar proposición')->assertSee('Crear bitácora')->assertSee('Agregar modelo')->assertDontSee('Add Posts');
    }

    public function test_history_is_scoped_to_active_system_and_sorted_by_date(): void
    {
        $this->record('Antigua','2026-10-01');
        $this->record('Reciente');
        $otherUser=User::factory()->create();
        Binnacle::create(['system_id'=>$this->otherSystem($otherUser)->id,'user_id'=>$otherUser->id,'recorded_on'=>'2026-10-13','title'=>'Ajena']);
        $this->get(route('admin.posts.index'))->assertOk()->assertSeeInOrder(['Reciente','Antigua'])->assertDontSee('Ajena');
    }

    private function otherSystem(User $user): System
    {
        $entity=Entity::create(['name'=>$user->name,'code'=>'OTHER-'.$user->id,'nature_id'=>Nature::first()->id]);
        $identity=Identity::create(['user_id'=>$user->id,'entity_id'=>$entity->id]);
        $system=System::create(['identity_id'=>$identity->id]);
        $system->users()->attach($user);
        return $system;
    }

    public function test_record_links_existing_proposition_model_and_activity_without_copying(): void
    {
        $project=Project::create(['system_id'=>$this->system->id,'name'=>'JFRV']);
        $activity=$project->activities()->create(['name'=>'Registrar trabajo']);
        $proposition=$this->actor->propositions()->create(['sentence'=>'Realicé una actividad.']);
        $model=$this->owner->models()->create(['name'=>'Activo']);
        $this->post(route('admin.binnacles.store'), ['system_id'=>$this->system->id,'project_id'=>$project->id,
            'title'=>'Jornada','recorded_on'=>'2026-10-13','proposition_ids'=>[$proposition->id],
            'model_ids'=>[$model->id],'activity_ids'=>[$activity->id]])->assertSessionHasNoErrors()->assertRedirect();
        $record=Binnacle::firstOrFail();
        $this->assertCount(1,$record->propositions);
        $this->assertCount(1,$record->models);
        $this->assertCount(1,$record->activities);
        $this->assertSame($this->system->id,$proposition->fresh()->systems->first()->id);
        $this->get(route('admin.binnacles.show',$record))->assertOk()->assertSee('Realicé una actividad.');
    }

    public function test_proposition_is_added_through_existing_flow_and_returns_to_binnacle(): void
    {
        $record=$this->record();
        $this->post(route('propositions.store'),['sentence'=>'  Necesito reparar el equipo.  ','binnacle_id'=>$record->id])
            ->assertSessionHasNoErrors()->assertRedirect(route('admin.binnacles.show',$record));
        $this->assertSame('Necesito reparar el equipo.',$record->propositions()->first()->sentence);
        $this->assertSame($this->actor->id,$record->propositions()->first()->actor_id);
    }

    public function test_proposition_from_overview_can_be_assigned_when_creating_binnacle(): void
    {
        $this->post(route('propositions.store'),['sentence'=>'Preparar el informe.','system_id'=>$this->system->id])->assertSessionHasNoErrors()->assertRedirect(route('admin.posts.index'));
        $proposition=$this->actor->propositions()->firstOrFail();
        $this->get(route('admin.binnacles.create'))->assertOk()->assertSee('Preparar el informe.');
        $this->assertSame($this->system->id,$proposition->systems->first()->id);
    }

    public function test_original_proposition_flow_without_binnacle_remains_available(): void
    {
        $this->post(route('propositions.store'),['sentence'=>'Una oración.'])->assertSessionHasNoErrors()->assertRedirect(route('propositions.index'));
        $this->assertSame(1,$this->actor->propositions()->count());
    }

    public function test_foreign_user_cannot_access_or_add_records_to_binnacle(): void
    {
        $record=$this->record();
        $this->actingAs(User::factory()->create());
        $this->get(route('admin.binnacles.show',$record))->assertNotFound();
        $this->get(route('admin.binnacles.edit',$record))->assertNotFound();
        $this->post(route('admin.binnacle-models.store'),['binnacle_id'=>$record->id,'name'=>'Invasión'])->assertNotFound();
    }

    public function test_foreign_models_and_propositions_are_rejected(): void
    {
        $other=User::factory()->create();
        $system=$this->otherSystem($other);
        $actor=Actor::create(['identity_id'=>$system->identity_id]);
        $proposition=$actor->propositions()->create(['sentence'=>'Ajena']);
        $model=$other->models()->create(['name'=>'Ajeno']);
        $this->post(route('admin.binnacles.store'), ['system_id'=>$this->system->id,'recorded_on'=>'2026-10-13','title'=>'Jornada',
            'proposition_ids'=>[$proposition->id],'model_ids'=>[$model->id]])->assertSessionHasErrors(['proposition_ids.0','model_ids.0']);
        $this->assertSame(0,Binnacle::count());
    }

    public function test_model_is_created_in_existing_models_table_and_linked(): void
    {
        $record=$this->record();
        $this->post(route('admin.binnacle-models.store'),['binnacle_id'=>$record->id,'name'=>'Equipo','description'=>'Activo comunicacional'])
            ->assertSessionHasNoErrors()->assertRedirect(route('admin.binnacles.show',$record));
        $this->assertSame('Equipo',$record->models()->first()->name);
        $this->assertSame($this->owner->id,$record->models()->first()->user_id);
    }

    public function test_updating_summary_keeps_previously_linked_history(): void
    {
        $record=$this->record();
        $proposition=$this->actor->propositions()->create(['sentence'=>'Registro previo']);
        $record->propositions()->attach($proposition);
        $this->put(route('admin.binnacles.update',$record),['recorded_on'=>'2026-10-13','title'=>'Revisada','summary'=>'Resumen actualizado'])
            ->assertSessionHasNoErrors();
        $this->assertSame(1,$record->propositions()->count());
        $this->assertSame('Resumen actualizado',$record->fresh()->summary);
    }

    public function test_context_creation_and_independent_proposition_tool_are_not_exposed(): void
    {
        $this->get(route('admin.mewo.contexts.index'))->assertOk()->assertDontSee('Crear contexto ampliado');
        $this->post('/admin/mewo/contexts',[])->assertStatus(405);
        $this->get('/admin/mewo/classification')->assertNotFound();
    }

    public function test_empty_and_invalid_records_are_rejected(): void
    {
        $this->post(route('admin.binnacles.store'),['system_id'=>$this->system->id,'recorded_on'=>'bad','title'=>' '])
            ->assertSessionHasErrors(['recorded_on','title']);
        $this->post(route('propositions.store'),['sentence'=>'   '])->assertSessionHasErrors('sentence');
    }

    public function test_forms_render_with_binnacle_context_and_summary_is_escaped(): void
    {
        $record=$this->record();
        $record->update(['summary'=>'<script>alert(1)</script>']);
        $this->get(route('admin.binnacles.create'))->assertOk();
        $this->get(route('admin.binnacles.edit',$record))->assertOk();
        $this->get(route('admin.binnacle-models.create',['binnacle_id'=>$record->id]))->assertOk();
        $this->get(route('propositions.create',['binnacle_id'=>$record->id]))->assertOk()->assertSee('name="binnacle_id"',false);
        $this->get(route('admin.binnacles.show',$record))->assertOk()->assertDontSee('<script>alert(1)</script>',false)->assertSee('&lt;script&gt;',false);
    }

    public function test_switching_system_changes_history(): void
    {
        $this->record('Sistema uno');
        $otherUser=User::factory()->create();
        $otherSystem=$this->otherSystem($otherUser);
        $otherSystem->users()->attach($this->owner);
        Binnacle::create(['system_id'=>$otherSystem->id,'user_id'=>$otherUser->id,'recorded_on'=>'2026-10-13','title'=>'Sistema dos']);
        $this->post(route('admin.system.active'),['system_id'=>$otherSystem->id])->assertRedirect();
        $this->get(route('admin.posts.index'))->assertOk()->assertSee('Sistema dos')->assertDontSee('Sistema uno');
    }
}
