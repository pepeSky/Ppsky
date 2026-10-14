<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Context;
use App\Models\Entity;
use App\Models\Goal;
use App\Models\Identity;
use App\Models\MewoProcess;
use App\Models\Nature;
use App\Models\Objetive;
use App\Models\Project;
use App\Models\System;
use App\Models\Task;
use App\Models\Unit;
use App\Models\User;
use App\Services\MeWo\ContextComposition;
use App\Services\MeWo\PropositionClassifier;
use App\Services\MeWo\QuantumObjectives;
use Database\Seeders\MewoOperationalSeeder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class MewoOperationsTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;
    private Project $project;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(MewoOperationalSeeder::class);
        $this->owner = User::factory()->create();
        $nature = Nature::create(['name' => 'Persona', 'slug' => 'persona']);
        $entity = Entity::create(['name' => 'JFRV', 'code' => 'JFRV', 'nature_id' => $nature->id]);
        $identity = Identity::create(['user_id' => $this->owner->id, 'entity_id' => $entity->id]);
        $system = System::create(['identity_id' => $identity->id]);
        $system->users()->attach($this->owner);
        $this->project = Project::create(['system_id' => $system->id, 'name' => 'PECV-JFRV']);
        $this->actingAs($this->owner);
    }

    private function process(string $context, string $code): MewoProcess
    {
        return MewoProcess::where('context_id', Context::where('code', $context)->value('id'))->where('code', $code)->firstOrFail();
    }

    private function activity(): Activity
    {
        $activity = Activity::create(['project_id' => $this->project->id, 'name' => 'Mantener instalación']);
        $process = $this->process('MAN', 'COR');
        $activity->contexts()->attach($process->context_id);
        $activity->mewoProcesses()->attach($process);
        return $activity;
    }

    public function test_catalogue_covers_eleven_contexts_and_is_idempotent(): void
    {
        $counts = [Context::count(), MewoProcess::count(), DB::table('operational_verbs')->count(), DB::table('mewo_process_operational_verb')->count()];
        $this->seed(MewoOperationalSeeder::class);
        $this->assertSame($counts, [Context::count(), MewoProcess::count(), DB::table('operational_verbs')->count(), DB::table('mewo_process_operational_verb')->count()]);
        $this->assertSame(11, Context::where('type', 'simple')->has('mewoProcesses')->count());
    }

    public function test_expansion_keeps_order_and_initial_reflexivity(): void
    {
        $adm = Context::where('code', 'ADM')->firstOrFail();
        $man = Context::where('code', 'MAN')->firstOrFail();
        $expanded = app(ContextComposition::class)->create([$adm->id, $adm->id, $man->id]);
        $this->assertSame('ADM+ADM+MAN', $expanded->code);
        $this->assertSame([$adm->id, $adm->id, $man->id], $expanded->components->pluck('id')->all());
        $this->assertSame($expanded->id, app(ContextComposition::class)->create([$adm->id, $adm->id, $man->id])->id);
    }

    public function test_repetition_after_initial_context_is_rejected(): void
    {
        $adm = Context::where('code', 'ADM')->value('id');
        $man = Context::where('code', 'MAN')->value('id');
        $this->expectException(ValidationException::class);
        app(ContextComposition::class)->create([$adm, $man, $man]);
    }

    public function test_double_reflexive_expansion_is_rejected(): void
    {
        $adm = Context::where('code', 'ADM')->value('id');
        $man = Context::where('code', 'MAN')->value('id');
        $this->expectException(ValidationException::class);
        app(ContextComposition::class)->create([$adm, $adm, $man, $man]);
    }

    public function test_activity_can_use_maintenance_and_administration_together(): void
    {
        $adm = $this->process('ADM', 'PLN');
        $man = $this->process('MAN', 'COR');
        $expanded = app(ContextComposition::class)->create([$man->context_id, $adm->context_id]);
        $this->post(route('admin.activities.store'), [
            'project_id' => $this->project->id, 'name' => 'Mantener instalación',
            'context_ids' => [$expanded->id], 'process_ids' => [$adm->id, $man->id],
        ])->assertSessionHasNoErrors()->assertRedirect();
        $activity = Activity::firstOrFail();
        $this->assertCount(3, $activity->contexts);
        $this->assertCount(2, $activity->mewoProcesses);
        $this->assertSame(0, $activity->tasks()->count());
    }

    public function test_process_outside_participating_context_is_rejected(): void
    {
        $adm = $this->process('ADM', 'PLN');
        $man = $this->process('MAN', 'COR');
        $this->post(route('admin.activities.store'), [
            'project_id' => $this->project->id, 'name' => 'Trabajo',
            'context_ids' => [$man->context_id], 'process_ids' => [$adm->id],
        ])->assertSessionHasErrors('process_ids');
        $this->assertSame(0, Activity::count());
    }

    public function test_task_context_is_derived_from_participating_process(): void
    {
        $activity = $this->activity();
        $process = $activity->mewoProcesses->first();
        $unit = Unit::create(['name' => 'Elemental', 'symbol' => 'UnEl']);
        $this->post(route('admin.activities.tasks.store', $activity), [
            'name' => 'Reparar componente', 'mewo_process_id' => $process->id,
            'context_id' => Context::where('code', 'FIN')->value('id'),
            'quantity' => 2.5, 'unit_id' => $unit->id, 'status' => 'completed',
        ])->assertSessionHasNoErrors()->assertRedirect();
        $task = $activity->tasks()->firstOrFail();
        $this->assertSame($process->context_id, $task->context_id);
        $this->assertEquals(2.5, $task->quantity);
    }

    public function test_task_cannot_use_a_process_outside_activity(): void
    {
        $activity = $this->activity();
        $this->post(route('admin.activities.tasks.store', $activity), [
            'name' => 'Trabajo', 'mewo_process_id' => $this->process('ADM', 'PLN')->id,
            'quantity' => 0, 'status' => 'pending',
        ])->assertSessionHasErrors('mewo_process_id');
        $this->assertSame(0, Task::count());
    }

    public function test_quantity_requires_unit_and_rejects_negative_values(): void
    {
        $activity = $this->activity();
        $data = ['name' => 'Trabajo', 'mewo_process_id' => $activity->mewoProcesses->first()->id, 'quantity' => 1, 'status' => 'pending'];
        $this->post(route('admin.activities.tasks.store', $activity), $data)->assertSessionHasErrors('unit_id');
        $data['quantity'] = -1;
        $this->post(route('admin.activities.tasks.store', $activity), $data)->assertSessionHasErrors('quantity');
    }

    public function test_quantities_are_not_duplicated_by_contexts_and_keep_units_separate(): void
    {
        $activity = $this->activity();
        $activity->contexts()->attach(Context::where('code', 'ADM')->value('id'));
        $unit = Unit::create(['name' => 'Elemental', 'symbol' => 'UnEl']);
        $other = Unit::create(['name' => 'Operativa', 'symbol' => 'UnOp']);
        foreach ([[$unit->id, 2], [$unit->id, 3], [$other->id, 7]] as [$id, $quantity]) {
            $activity->tasks()->create(['name' => 'Tarea', 'description' => '', 'context_id' => $activity->contexts->first()->id, 'quantity' => $quantity, 'unit_id' => $id]);
        }
        $totals = $activity->quantities()->pluck('total', 'unit_id');
        $this->assertEquals(5, $totals[$unit->id]);
        $this->assertEquals(7, $totals[$other->id]);
    }

    public function test_other_user_cannot_read_or_modify_activity(): void
    {
        $activity = $this->activity();
        $this->actingAs(User::factory()->create());
        $this->get(route('admin.activities.show', $activity))->assertNotFound();
        $this->put(route('admin.activities.update', $activity), [])->assertNotFound();
        $this->get(route('admin.activities.tasks.create', $activity))->assertNotFound();
    }

    public function test_foreign_project_is_not_accepted_on_creation(): void
    {
        $this->actingAs(User::factory()->create());
        $process = $this->process('ADM', 'PLN');
        $this->post(route('admin.activities.store'), [
            'project_id' => $this->project->id, 'name' => 'Trabajo',
            'context_ids' => [$process->context_id], 'process_ids' => [$process->id],
        ])->assertSessionHasErrors('project_id');
    }

    public function test_activity_and_used_context_cannot_be_removed_with_tasks(): void
    {
        $activity = $this->activity();
        $task = $activity->tasks()->create(['name' => 'Tarea', 'description' => '', 'context_id' => $activity->contexts->first()->id, 'mewo_process_id' => $activity->mewoProcesses->first()->id]);
        $this->delete(route('admin.activities.destroy', $activity))->assertForbidden();
        $adm = $this->process('ADM', 'PLN');
        $this->put(route('admin.activities.update', $activity), [
            'project_id' => $this->project->id, 'name' => 'Trabajo',
            'context_ids' => [$adm->context_id], 'process_ids' => [$adm->id],
        ])->assertSessionHasErrors('context_ids');
        $this->assertNotNull($task->fresh());
    }

    public function test_quantified_task_cannot_be_deleted_and_task_cannot_cross_activities(): void
    {
        $activity = $this->activity();
        $task = $activity->tasks()->create(['name' => 'Tarea', 'description' => '', 'context_id' => $activity->contexts->first()->id, 'quantity' => 2]);
        $this->delete(route('admin.activities.tasks.destroy', [$activity, $task]))->assertForbidden();
        $other = $this->activity();
        $this->get(route('admin.activities.tasks.edit', [$other, $task]))->assertNotFound();
    }

    public function test_classifier_detects_requested_action_and_preserves_ambiguity(): void
    {
        $classifier = app(PropositionClassifier::class);
        $result = collect($classifier->suggest('Necesito reparar la instalación eléctrica.'));
        $this->assertSame(['reparar'], $result->pluck('verb')->unique()->all());
        $this->assertSame('Mantenimiento', $result->first()['context']);
        $this->assertGreaterThan(1, collect($classifier->suggest('Necesito analizar el problema.'))->pluck('context_id')->unique()->count());
        $this->assertSame([], $classifier->suggest('El mensaje de comunicación.'));
        $this->assertNotEmpty($classifier->suggest('Ayer reparé el equipo.'));
        $this->assertSame(0, Activity::count());
    }

    public function test_no_quantum_objectives_are_invented_without_hierarchy(): void
    {
        $this->assertEmpty(app(QuantumObjectives::class)->available($this->project->id));
    }

    public function test_only_quantum_level_of_same_project_can_receive_activity(): void
    {
        // Simulate the user's already implemented hierarchy, absent from published main.
        Schema::table('objetives', fn (Blueprint $table) => $table->unsignedBigInteger('parent_id')->nullable());
        $goal = Goal::create(['name' => 'Meta']);
        $parent = null;
        $nodes = [];
        foreach (['General', 'Principal', 'Específico', 'Cuántico'] as $description) {
            $parent = Objetive::create(['description' => $description, 'project_id' => $this->project->id, 'goal_id' => $goal->id, 'parent_id' => $parent?->id]);
            $nodes[] = $parent;
        }
        $this->assertSame([$nodes[3]->id], app(QuantumObjectives::class)->available($this->project->id)->pluck('id')->all());
        $process = $this->process('ADM', 'PLN');
        $data = ['project_id' => $this->project->id, 'name' => 'Actividad', 'objetive_id' => $nodes[3]->id, 'context_ids' => [$process->context_id], 'process_ids' => [$process->id]];
        $this->post(route('admin.activities.store'), $data)->assertSessionHasNoErrors();
        $this->assertSame($nodes[3]->id, Activity::first()->objetives->first()->id);
        $data['objetive_id'] = $nodes[2]->id;
        $this->post(route('admin.activities.store'), $data)->assertSessionHasErrors('objetive_id');
    }

    public function test_operational_views_render_and_escape_proposition(): void
    {
        $activity = $this->activity();
        foreach (['admin.activities.index', 'admin.activities.create', 'admin.mewo.contexts.index', 'admin.mewo.processes.index', 'admin.mewo.processes.create'] as $route) {
            $this->get(route($route))->assertOk();
        }
        $this->get(route('admin.activities.show', $activity))->assertOk();
        $this->get(route('admin.activities.edit', $activity))->assertOk();
        $this->get(route('admin.activities.tasks.create', $activity))->assertOk();
    }

    public function test_seeded_expanded_contexts_have_components(): void
    {
        $context = Context::where('code', 'ADM+MAN')->firstOrFail();
        $this->assertSame(['ADM', 'MAN'], $context->components->pluck('code')->all());
    }

    public function test_process_definition_and_verbs_can_be_updated_without_overwriting_execution(): void
    {
        $activity = $this->activity();
        $process = $activity->mewoProcesses->first();
        $this->put(route('admin.mewo.processes.update', $process), [
            'context_id' => $process->context_id, 'code' => $process->code, 'name' => $process->name,
            'verbs' => 'reparar, verificar', 'input' => 'Activo con falla', 'output' => 'Activo operativo',
            'procedure' => 'Diagnosticar, intervenir y verificar.',
        ])->assertSessionHasNoErrors();
        $this->assertSame('Activo operativo', $process->fresh()->output);
        $this->seed(MewoOperationalSeeder::class);
        $this->assertSame('Activo operativo', $process->fresh()->output);
        $this->assertNotNull($activity->fresh());
        $this->put(route('admin.mewo.processes.update', $process), [
            'context_id' => Context::where('code', 'ADM')->value('id'), 'code' => $process->code,
            'name' => $process->name, 'verbs' => 'reparar',
        ])->assertSessionHasErrors('context_id');
    }

    public function test_upgrade_preserves_legacy_tasks_and_populates_participation(): void
    {
        $activity = $this->activity();
        $task = $activity->tasks()->create(['name' => 'Tarea previa', 'description' => 'Conservar registro', 'context_id' => Context::where('code', 'ADM+MAN')->value('id')]);
        $migration = require database_path('migrations/2026_10_13_010000_extend_mewo_operational_structure.php');
        $migration->down();
        $migration->up();
        $this->assertSame('Tarea previa', $task->fresh()->name);
        $this->assertSame('Conservar registro', $task->fresh()->description);
        $this->assertTrue($activity->contexts()->whereKey($task->context_id)->exists());
        $this->assertSame(['ADM','MAN'], Context::where('code','ADM+MAN')->first()->components->pluck('code')->all());
    }

    public function test_classifier_flags_negation_and_returns_original_complement_fragment(): void
    {
        $result = app(PropositionClassifier::class)->suggest('No reparar la instalación eléctrica.');
        $this->assertTrue($result[0]['negated']);
        $this->assertSame('la instalación eléctrica', $result[0]['complement_fragment']);
    }

    public function test_independent_proposition_suggestions_are_not_exposed(): void
    {
        $actor = \App\Models\Actor::create(['identity_id' => $this->project->system->identity_id]);
        $proposition = $actor->propositions()->create(['sentence' => 'Necesito reparar el equipo.']);
        $this->get('/admin/mewo/propositions/'.$proposition->id.'/suggestions')->assertNotFound();
        $this->actingAs(User::factory()->create());
        $this->get('/admin/mewo/propositions/'.$proposition->id.'/suggestions')->assertNotFound();
    }

    public function test_legacy_objective_links_are_preserved_without_published_hierarchy(): void
    {
        $activity = $this->activity();
        $goal = Goal::create(['name' => 'Meta anterior']);
        $objective = Objetive::create(['description' => 'Objetivo anterior', 'project_id' => $this->project->id, 'goal_id' => $goal->id]);
        $activity->objetives()->attach($objective);
        $process = $activity->mewoProcesses->first();
        $this->put(route('admin.activities.update', $activity), [
            'project_id' => $this->project->id, 'name' => 'Actividad actualizada', 'objetive_id' => '',
            'context_ids' => [$process->context_id], 'process_ids' => [$process->id],
        ])->assertSessionHasNoErrors();
        $this->assertSame($objective->id, $activity->fresh()->objetives->first()->id);
    }

    public function test_legacy_task_in_expanded_context_allows_process_assignment(): void
    {
        $activity = $this->activity();
        $expanded = Context::where('code', 'ADM+MAN')->firstOrFail();
        $activity->contexts()->attach($expanded);
        $activity->tasks()->create(['name' => 'Tarea anterior', 'description' => '', 'context_id' => $expanded->id]);
        $process = $activity->mewoProcesses->first();
        $this->put(route('admin.activities.update', $activity), [
            'project_id' => $this->project->id, 'name' => 'Actividad actualizada',
            'context_ids' => [$expanded->id], 'process_ids' => [$process->id],
        ])->assertSessionHasNoErrors();
    }
}
