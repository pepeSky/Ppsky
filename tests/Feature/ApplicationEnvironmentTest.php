<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Entity;
use App\Models\Identity;
use App\Models\Nature;
use App\Models\System;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\Admin\ApplicationModels;
use App\Livewire\Admin\ApplicationDictionary;
use App\Livewire\Admin\DictionaryTransfer;
use App\Services\MeWo\DictionaryTransfer as Transfer;
use Illuminate\Http\UploadedFile;
use App\Models\Language;
use App\Models\Definition;

class ApplicationEnvironmentTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;
    private System $system;

    protected function setUp(): void
    {
        parent::setUp();
        $this->owner = User::factory()->create();
        $nature = Nature::create(['name' => 'Persona', 'slug' => 'persona']);
        $entity = Entity::create(['name' => 'Sistema de prueba', 'code' => 'TEST', 'nature_id' => $nature->id]);
        $identity = Identity::create(['user_id' => $this->owner->id, 'entity_id' => $entity->id]);
        $this->system = System::create(['identity_id' => $identity->id]);
        $this->system->users()->attach($this->owner);
        $this->actingAs($this->owner);
    }

    private function application(): Application
    {
        return $this->owner->applications()->create(['name' => 'App distinta', 'system_id' => $this->system->id]);
    }

    public function test_application_records_system_and_platforms_and_displays_distinct_names(): void
    {
        $this->get(route('admin.applications.create'))->assertOk()->assertSee('Sistema de prueba')->assertSee('Plataformas compatibles');
        $this->post(route('admin.applications.store'), ['name' => 'App distinta', 'compatible_platforms' => ['web', 'macos'], 'execution_requirements' => 'Navegador compatible'])->assertRedirect();
        $app = Application::first();
        $this->assertSame(['web', 'macos'], $app->compatible_platforms);
        $this->get(route('admin.applications.show', $app))->assertOk()->assertSee('Sistema de prueba')->assertSee('App distinta')->assertSee('macOS')->assertDontSee('Requisitos de ejecución');
        $this->get(route('admin.applications.index'))->assertOk()->assertSee('Plataformas compatibles')->assertDontSee('Modelos utilizados');
    }

    public function test_creating_model_associates_it_without_detaching_existing_terms(): void
    {
        $app = $this->application();
        $model = $this->owner->models()->create(['name' => 'Anterior', 'description' => 'Referencia anterior']);
        $app->models()->attach($model);
        $this->post(route('admin.applications.models.store', $app), ['model_name' => 'Playa', 'model_description' => 'Mi referencia comunicacional'])->assertRedirect(route('admin.applications.edit', $app));
        $this->assertSame(2, $app->models()->count());
        $this->assertDatabaseHas('models', ['user_id' => $this->owner->id, 'name' => 'Playa', 'description' => 'Mi referencia comunicacional']);
        $this->get(route('admin.applications.edit', $app))->assertOk()->assertSee('Agregar modelo')->assertSee('2 modelos')->assertSee('Ver diccionario');
        $this->get(route('admin.applications.show', $app))->assertOk()->assertSee('2 modelos');
        $this->get(route('admin.applications.dictionary', ['application' => $app, 'term' => $app->models()->where('name', 'Playa')->first()->id]))->assertOk()->assertSee('Playa')->assertSee('Mi referencia comunicacional');
    }

    public function test_foreign_owner_cannot_add_model_and_foreign_model_cannot_be_attached(): void
    {
        $app = $this->application();
        $foreign = User::factory()->create();
        $model = $foreign->models()->create(['name' => 'Ajeno']);
        $this->put(route('admin.applications.update', $app), ['name' => $app->name, 'model_ids' => [$model->id]])->assertSessionHasErrors('model_ids');
        $this->actingAs($foreign)->post(route('admin.applications.models.store', $app), ['model_name' => 'Invasión', 'model_description' => 'No autorizado'])->assertNotFound();
        $this->assertDatabaseMissing('models', ['name' => 'Invasión']);
    }

    public function test_unknown_platform_inaccessible_system_and_duplicate_term_are_rejected(): void
    {
        $app = $this->application();
        $this->put(route('admin.applications.update', $app), ['name' => $app->name, 'compatible_platforms' => ['http']])->assertSessionHasErrors('compatible_platforms.0');
        $this->put(route('admin.applications.update', $app), ['name' => $app->name, 'system_id' => 99999])->assertSessionHasErrors('system_id');
        $this->owner->models()->create(['name' => 'Playa', 'description' => 'Original']);
        $this->post(route('admin.applications.models.store', $app), ['model_name' => 'Playa', 'model_description' => 'Cambiar significado'])->assertSessionHasErrors('model_name');
        $this->assertDatabaseHas('models', ['name' => 'Playa', 'description' => 'Original']);
    }

    public function test_legacy_app_is_not_automatically_assigned_and_platforms_can_be_cleared(): void
    {
        $app = $this->owner->applications()->create(['name' => 'Histórica', 'compatible_platforms' => ['web']]);
        $this->get(route('admin.applications.show', $app))->assertOk()->assertSee('Sin sistema asignado');
        $this->assertNull($app->fresh()->system_id);
        $this->put(route('admin.applications.update', $app), ['name' => $app->name])->assertRedirect();
        $this->assertSame([], $app->fresh()->compatible_platforms);
        $this->assertEquals($this->system->id, $app->fresh()->system_id);
    }

    public function test_active_system_is_fixed_on_create_and_cannot_be_changed_from_form(): void
    {
        $this->withSession(['active_system_id' => $this->system->id]);
        $this->get(route('admin.applications.create'))->assertOk()->assertDontSee('name="system_id"', false)->assertDontSee('model_ids');
        $this->post(route('admin.applications.store'), ['name' => 'Falsificada', 'system_id' => 999])->assertSessionHasErrors('system_id');
        $this->assertDatabaseMissing('applications', ['name' => 'Falsificada']);
        $this->post(route('admin.applications.store'), ['name' => 'Local'])->assertRedirect();
        $this->assertDatabaseHas('applications', ['name' => 'Local.MeWo', 'system_id' => $this->system->id]);
        $app = $this->application();
        $this->put(route('admin.applications.update', $app), ['name' => 'Cambio', 'system_id' => 999])->assertSessionHasErrors('system_id');
        $this->assertEquals($this->system->id, $app->fresh()->system_id);
    }

    public function test_switching_dashboard_system_hides_and_blocks_applications_from_previous_system(): void
    {
        $app = $this->application();
        $entity = Entity::create(['name' => 'Otro sistema', 'code' => 'OTHER', 'nature_id' => Nature::first()->id]);
        $identity = Identity::create(['user_id' => $this->owner->id, 'entity_id' => $entity->id]);
        $other = System::create(['identity_id' => $identity->id]);
        $other->users()->attach($this->owner);
        $this->withSession(['active_system_id' => $other->id]);
        $this->get(route('admin.applications.index'))->assertOk()->assertDontSee($app->name)->assertSee('Otro sistema');
        foreach (['show', 'edit', 'dictionary'] as $action) {
            $this->get(route('admin.applications.'.$action, $app))->assertNotFound();
        }
        $this->post(route('admin.applications.models.store', $app), ['model_name' => 'Ajeno', 'model_description' => 'Referencia'])->assertNotFound();
        Livewire::test(ApplicationModels::class, ['applicationId' => $app->id])->assertStatus(404);
    }

    public function test_jetstream_popup_creates_model_in_place_and_application_save_keeps_associations(): void
    {
        $app = $this->application();
        $this->get(route('admin.applications.edit', $app))->assertOk()->assertSee('jetstream-modal')->assertSee('application-model-popup')->assertDontSee('model_ids');
        Livewire::test(ApplicationModels::class, ['applicationId' => $app->id])
            ->call('open')->assertSet('addingModel', true)
            ->set('name', 'Playa')->set('description', 'Referencia personal')
            ->call('save')->assertHasNoErrors()->assertSet('addingModel', false)->assertSee('1 modelo')->assertNoRedirect();
        $this->put(route('admin.applications.update', $app), ['name' => 'App modificada'])->assertRedirect();
        $this->assertSame(1, $app->models()->count());
        $this->assertSame('Playa', $app->models()->first()->name);
    }

    public function test_popup_can_reuse_model_and_rejects_foreign_model(): void
    {
        $app = $this->application();
        $term = $this->owner->models()->create(['name' => 'Reutilizable', 'description' => 'Original']);
        Livewire::test(ApplicationModels::class, ['applicationId' => $app->id])->call('open')
            ->set('existingModelId', $term->id)->call('save')->assertHasNoErrors();
        $this->assertSame(1, $this->owner->models()->count());
        $foreign = User::factory()->create()->models()->create(['name' => 'Privado']);
        Livewire::test(ApplicationModels::class, ['applicationId' => $app->id])->call('open')
            ->set('existingModelId', $foreign->id)->call('save')->assertHasErrors('existingModelId');
        $this->assertSame(1, $app->models()->count());
    }

    public function test_dictionary_search_definitions_languages_and_term_isolation(): void
    {
        $app = $this->application();
        $term = $this->owner->models()->create(['name' => 'Playa', 'description' => 'Referencia personal']);
        $other = $this->owner->models()->create(['name' => 'Montaña', 'description' => 'No asociada']);
        $app->models()->attach($term);
        $language = Language::create(['name' => 'Español', 'code' => 'es']);
        Definition::create(['model_id' => $term->id, 'language_id' => $language->id, 'definition' => 'Orilla del mar']);
        $this->get(route('admin.applications.show', $app))->assertOk()->assertSee('Español')->assertSee('1 modelo');
        $this->get(route('admin.applications.dictionary', ['application' => $app, 'q' => 'Orilla']))->assertOk()->assertSee('Playa')->assertSee('Orilla del mar')->assertSee('Español')->assertDontSee('No asociada');
        $this->get(route('admin.applications.dictionary', ['application' => $app, 'term' => $other->id]))->assertNotFound();
        $this->get(route('admin.applications.dictionary', ['application' => $app, 'q' => 'Inexistente']))->assertOk()->assertSee('No hay términos coincidentes');
    }

    public function test_popup_rechecks_active_system_after_dashboard_switch(): void
    {
        $app = $this->application();
        $component = Livewire::test(ApplicationModels::class, ['applicationId' => $app->id])->call('open')
            ->set('name', 'No crear')->set('description', 'Cambio de sistema');
        $entity = Entity::create(['name' => 'Segundo sistema', 'code' => 'SECOND', 'nature_id' => Nature::first()->id]);
        $identity = Identity::create(['user_id' => $this->owner->id, 'entity_id' => $entity->id]);
        $other = System::create(['identity_id' => $identity->id]);
        $other->users()->attach($this->owner);
        session()->put('active_system_id', $other->id);
        $component->call('save')->assertStatus(404);
        $this->assertDatabaseMissing('models', ['name' => 'No crear']);
    }

    public function test_edit_loads_livewire_script_once_with_adminlte_integration_enabled(): void
    {
        config(['adminlte.livewire' => true]);
        $response = $this->get(route('admin.applications.edit', $this->application()))->assertOk();
        preg_match_all('/<script[^>]+src=["\'][^"\']*livewire(?:\.min)?\.js[^"\']*["\']/i', $response->getContent(), $scripts);
        $this->assertCount(1, $scripts[0], 'La vista debe cargar Livewire una sola vez.');
    }

    public function test_edit_loads_livewire_script_once_without_adminlte_integration(): void
    {
        config(['adminlte.livewire' => false]);
        $response = $this->get(route('admin.applications.edit', $this->application()))->assertOk();
        preg_match_all('/<script[^>]+src=["\'][^"\']*livewire(?:\.min)?\.js[^"\']*["\']/i', $response->getContent(), $scripts);
        $this->assertCount(1, $scripts[0]);
    }

    public function test_dictionary_selects_and_searches_in_place_without_redirect(): void
    {
        $app = $this->application();
        $a = $this->owner->models()->create(['name' => 'Lenguaje', 'description' => 'Primer significado']);
        $b = $this->owner->models()->create(['name' => 'MeWo', 'description' => 'Segundo significado']);
        $app->models()->attach([$a->id, $b->id]);
        Livewire::test(ApplicationDictionary::class, ['applicationId' => $app->id])
            ->assertSee('Primer significado')->assertSee('wire:click.prevent', false)
            ->call('selectTerm', $b->id)->assertSet('term', $b->id)
            ->assertSee('Segundo significado')->assertDontSee('Primer significado')->assertNoRedirect()
            ->set('search', 'Lenguaje')->assertSet('term', null)
            ->assertSee('Primer significado')->assertDontSee('Segundo significado')->assertNoRedirect()
            ->set('search', 'No existe')->assertSee('No hay términos coincidentes');
        $foreign = $this->owner->models()->create(['name' => 'Fuera de la app']);
        Livewire::test(ApplicationDictionary::class, ['applicationId' => $app->id])->call('selectTerm', $foreign->id)->assertStatus(404);
    }

    public function test_dictionary_paginates_without_redirect_and_resets_search_to_first_page(): void
    {
        $app = $this->application();
        for ($i = 1; $i <= 31; $i++) {
            $term = $this->owner->models()->create(['name' => sprintf('Termino %02d', $i), 'description' => sprintf('Referencia %02d', $i)]);
            $app->models()->attach($term);
        }
        Livewire::test(ApplicationDictionary::class, ['applicationId' => $app->id])
            ->assertSee('Referencia 01')->call('setPage', 2)->assertSee('Referencia 31')->assertNoRedirect()
            ->set('search', 'Termino 01')->assertSet('paginators.page', 1)->assertSee('Referencia 01')->assertNoRedirect();
        $response = $this->get(route('admin.applications.dictionary', $app))->assertOk();
        preg_match_all('/<script[^>]+src=["\'][^"\']*livewire(?:\.min)?\.js[^"\']*["\']/i', $response->getContent(), $scripts);
        $this->assertCount(1, $scripts[0]);
    }

    public function test_dictionary_imports_selected_terms_without_duplicates(): void
    {
        $target = $this->application();
        $source = $this->owner->applications()->create(['name' => 'Origen', 'system_id' => $this->system->id]);
        $term = $this->owner->models()->create(['name' => 'MeWo', 'description' => 'Referencia original']);
        $source->models()->attach($term);
        $component = Livewire::test(DictionaryTransfer::class, ['applicationId' => $target->id])
            ->call('open')->set('sourceId', $source->id)->set('selectedIds', [$term->id])
            ->call('importSelected')->assertHasNoErrors()->assertDispatched('dictionary-updated');
        $this->assertSame(1, $target->models()->count());
        $component->call('importSelected')->assertHasNoErrors();
        $this->assertSame(1, $target->models()->count());
        $this->assertSame(1, $this->owner->models()->count());
        $this->assertSame('Referencia original', $target->models()->first()->description);
        $extra = $this->owner->models()->create(['name' => 'No está en origen']);
        Livewire::test(DictionaryTransfer::class, ['applicationId' => $target->id])->set('sourceId', $source->id)->set('selectedIds', [$extra->id])
            ->call('importSelected')->assertHasErrors('selectedIds');
    }

    public function test_dictionary_json_roundtrip_preserves_definitions_and_languages(): void
    {
        $source = $this->application();
        $model = $this->owner->models()->create(['name' => 'Playa', 'description' => 'Mi referencia']);
        $source->models()->attach($model);
        $language = Language::create(['name' => 'Español', 'type' => 'natural', 'code' => 'es']);
        Definition::create(['model_id' => $model->id, 'language_id' => $language->id, 'definition' => 'Orilla del mar']);
        $payload = app(Transfer::class)->export($source);
        $this->assertSame('es', $payload['terms'][0]['definitions'][0]['language']['code']);
        Livewire::test(DictionaryTransfer::class, ['applicationId' => $source->id])->call('export')->assertFileDownloaded('diccionario-app-'.$source->id.'.json');
        $target = $this->owner->applications()->create(['name' => 'Destino', 'system_id' => $this->system->id]);
        $file = UploadedFile::fake()->createWithContent('diccionario.json', json_encode($payload));
        Livewire::test(DictionaryTransfer::class, ['applicationId' => $target->id])->set('file', $file)->call('importFile')
            ->assertHasNoErrors()->assertDispatched('dictionary-updated');
        $this->assertSame(1, $target->models()->count());
        $this->assertSame(1, $model->definitions()->count());
        $this->assertSame('Orilla del mar', $target->models()->first()->definitions()->first()->definition);
    }

    public function test_json_import_creates_terms_and_omits_conflicts_without_overwriting(): void
    {
        $target = $this->application();
        $model = $this->owner->models()->create(['name' => 'MeWo', 'description' => 'Original']);
        $language = Language::create(['name' => 'Español', 'type' => 'natural', 'code' => 'es']);
        $payload = ['format' => 'pepesky.dictionary', 'version' => 1, 'terms' => [
            ['name' => 'MeWo', 'description' => 'Conflicto', 'definitions' => []],
            ['name' => 'Playa', 'description' => 'Nueva referencia', 'definitions' => [
                ['definition' => 'Orilla del mar', 'language' => ['name' => 'Español', 'type' => 'natural', 'code' => 'es']],
            ]],
        ]];
        $result = app(Transfer::class)->import($this->owner, $target, $payload);
        $this->assertSame(['MeWo'], $result['conflicts']);
        $this->assertSame(1, $result['added']);
        $this->assertSame('Original', $model->fresh()->description);
        $this->assertSame(1, $target->models()->count());
        $this->assertSame(1, Language::where('name', 'Español')->count());
        $result = app(Transfer::class)->import($this->owner, $target, $payload);
        $this->assertSame(0, $result['added']);
        $this->assertSame(1, $target->models()->first()->definitions()->count());
    }

    public function test_import_blocks_other_system_source_and_invalid_json(): void
    {
        $target = $this->application();
        $entity = Entity::create(['name' => 'Otro sistema', 'code' => 'TRANSFER', 'nature_id' => Nature::first()->id]);
        $identity = Identity::create(['user_id' => $this->owner->id, 'entity_id' => $entity->id]);
        $other = System::create(['identity_id' => $identity->id]);
        $other->users()->attach($this->owner);
        $source = $this->owner->applications()->create(['name' => 'Origen ajeno al sistema activo', 'system_id' => $other->id]);
        Livewire::test(DictionaryTransfer::class, ['applicationId' => $target->id])->set('sourceId', $source->id)->set('selectedIds', [1])
            ->call('importSelected')->assertStatus(404);
        Livewire::test(DictionaryTransfer::class, ['applicationId' => $target->id])->set('file', UploadedFile::fake()->createWithContent('invalido.json', '{'))
            ->call('importFile')->assertHasErrors('file');
        Livewire::test(DictionaryTransfer::class, ['applicationId' => $target->id])->set('file', UploadedFile::fake()->createWithContent('formato.json', '{}'))
            ->call('importFile')->assertHasErrors('format');
        $this->assertSame(0, $target->models()->count());
    }

    public function test_json_import_handles_new_languages_and_conflicting_codes(): void
    {
        $target = $this->application();
        $payload = ['format' => 'pepesky.dictionary', 'version' => 1, 'terms' => [[
            'name' => 'Playa', 'description' => 'Referencia', 'definitions' => [[
                'definition' => 'Rivage', 'language' => ['name' => 'Français', 'code' => 'fr', 'description' => 'Lenguaje francés'],
            ]],
        ]]];
        app(Transfer::class)->import($this->owner, $target, $payload);
        $this->assertDatabaseHas('languages', ['name' => 'Français', 'code' => 'fr', 'description' => 'Lenguaje francés']);
        $payload['terms'][0]['name'] = 'Costa';
        $payload['terms'][0]['definitions'][0]['language']['code'] = 'different';
        $result = app(Transfer::class)->import($this->owner, $target, $payload);
        $this->assertSame(['Costa'], $result['conflicts']);
        $this->assertDatabaseMissing('models', ['name' => 'Costa']);
        $this->assertDatabaseHas('languages', ['name' => 'Français', 'code' => 'fr']);
    }

    public function test_term_toolbar_views_edits_and_logically_unlinks_without_deleting(): void
    {
        $app = $this->application();
        $model = $this->owner->models()->create(['name' => 'Inicial', 'description' => 'Antes']);
        $app->models()->attach($model);
        $component = Livewire::test(ApplicationDictionary::class, ['applicationId' => $app->id])
            ->assertSee('aria-label="Editar"', false)->assertSee('aria-label="Desvincular"', false)
            ->call('viewTerm', $model->id)->assertSet('viewing', true)
            ->call('editTerm', $model->id)->set('editName', 'Actualizado')->set('editDescription', 'Después')
            ->call('saveTerm')->assertHasNoErrors()->assertSet('editing', false);
        $this->assertSame('Después', $model->fresh()->description);
        $component->call('confirmUnlink', $model->id)->call('unlinkTerm')->assertHasNoErrors();
        $this->assertSame(0, $app->models()->count());
        $this->assertDatabaseHas('models', ['id' => $model->id]);
        $this->assertDatabaseHas('application_model', ['application_id' => $app->id, 'model_id' => $model->id, 'is_active' => false]);
        $changes = $app->associateModels([$model->id]);
        $this->assertCount(1, $changes['attached']);
        $this->assertSame(1, $app->models()->count());
        $this->assertSame(1, $app->modelAssociations()->count());
    }

    public function test_shared_term_is_not_edited_across_applications(): void
    {
        $app = $this->application();
        $other = $this->owner->applications()->create(['name' => 'Otra app', 'system_id' => $this->system->id]);
        $model = $this->owner->models()->create(['name' => 'Compartido', 'description' => 'Original']);
        $app->models()->attach($model);
        $other->models()->attach($model);
        Livewire::test(ApplicationDictionary::class, ['applicationId' => $app->id])->call('editTerm', $model->id)
            ->set('editDescription', 'No propagar')->call('saveTerm')->assertHasErrors('editDescription');
        $this->assertSame('Original', $model->fresh()->description);
        Livewire::test(ApplicationDictionary::class, ['applicationId' => $app->id])->call('confirmUnlink', $model->id)->call('unlinkTerm');
        $this->assertSame(1, $other->models()->count());
    }

    public function test_frontend_application_deletion_is_blocked_and_button_removed(): void
    {
        $app = $this->application();
        $this->get(route('admin.applications.index'))->assertOk()->assertDontSee('Eliminar');
        $this->delete(route('admin.applications.destroy', $app))->assertForbidden()->assertSee('administración de comunicación');
        $this->assertDatabaseHas('applications', ['id' => $app->id]);
    }

    public function test_phase1_alphabet_filters_accented_names_and_enye_in_place(): void
    {
        $app = $this->application();
        foreach (['Árbol', 'Nube', 'Ñandú', '9datos'] as $name) {
            $model = $this->owner->models()->create(['name' => $name, 'description' => 'Referencia '.$name]);
            $app->associateModels([$model->id]);
        }
        Livewire::test(ApplicationDictionary::class, ['applicationId' => $app->id])
            ->call('selectLetter', 'A')->assertSee('Árbol')->assertViewHas('terms', fn ($terms) => $terms->pluck('name')->all() === ['Árbol'])->assertNoRedirect()
            ->call('selectLetter', 'Ñ')->assertSee('Ñandú')->assertViewHas('terms', fn ($terms) => $terms->pluck('name')->all() === ['Ñandú'])
            ->call('selectLetter', '#')->assertSee('9datos')->assertViewHas('terms', fn ($terms) => $terms->pluck('name')->all() === ['9datos']);
        $this->get(route('admin.applications.dictionary', $app))->assertOk()->assertSee('Agregar término')->assertSee('Índice alfabético');
    }

    public function test_phase1_definition_adds_language_without_executing_representation(): void
    {
        $app = $this->application();
        $model = $this->owner->models()->create(['name' => 'Persona', 'description' => 'Mi referencia']);
        $app->associateModels([$model->id]);
        Livewire::test(ApplicationDictionary::class, ['applicationId' => $app->id])->call('openDefinition', $model->id)
            ->set('newLanguage', true)->set('languageName', 'Objective-C')->set('languageCode', 'objc')
            ->set('definitionText', '@interface Persona : NSObject')->call('saveDefinition')->assertHasNoErrors()->assertSet('addingDefinition', false);
        $this->assertDatabaseHas('languages', ['name' => 'Objective-C', 'code' => 'objc']);
        $this->assertSame(1, $model->definitions()->count());
        $this->get(route('admin.applications.show', $app))->assertOk()->assertSee('Objective-C');
        $language = Language::where('name', 'Objective-C')->first();
        Livewire::test(ApplicationDictionary::class, ['applicationId' => $app->id])->call('openDefinition', $model->id)
            ->set('languageId', $language->id)->set('definitionText', '@interface Persona : NSObject')->call('saveDefinition')->assertHasNoErrors();
        $this->assertSame(1, $model->definitions()->count());
    }

    public function test_phase1_relations_are_scoped_symmetric_and_preserved_when_deactivated(): void
    {
        $app = $this->application();
        $a = $this->owner->models()->create(['name' => 'A', 'description' => 'A']);
        $b = $this->owner->models()->create(['name' => 'B', 'description' => 'B']);
        $app->associateModels([$a->id, $b->id]);
        Livewire::test(ApplicationDictionary::class, ['applicationId' => $app->id])->call('openRelation', $a->id)
            ->set('relationTargetId', $b->id)->set('relationType', 'synonym')->call('saveRelation')->assertHasNoErrors();
        Livewire::test(ApplicationDictionary::class, ['applicationId' => $app->id])->call('openRelation', $b->id)
            ->set('relationTargetId', $a->id)->set('relationType', 'synonym')->call('saveRelation')->assertHasNoErrors()->assertSee('Sinónimo de');
        $this->assertDatabaseCount('application_model_relations', 1);
        $relation = \App\Models\ApplicationModelRelation::first();
        Livewire::test(ApplicationDictionary::class, ['applicationId' => $app->id])->call('deactivateRelation', $relation->id)->assertDontSee('Relaciones del modelo');
        $this->assertDatabaseHas('application_model_relations', ['id' => $relation->id, 'is_active' => false]);
        $foreign = $this->owner->models()->create(['name' => 'No asociado']);
        Livewire::test(ApplicationDictionary::class, ['applicationId' => $app->id])->call('openRelation', $a->id)
            ->set('relationTargetId', $foreign->id)->call('saveRelation')->assertStatus(404);
    }

    public function test_phase1_shared_models_cannot_receive_definitions_and_requirements_are_preserved(): void
    {
        $app = $this->application();
        $app->update(['execution_requirements' => 'Dato anterior']);
        $other = $this->owner->applications()->create(['name' => 'Otra', 'system_id' => $this->system->id]);
        $model = $this->owner->models()->create(['name' => 'Compartido', 'description' => 'Referencia']);
        $app->associateModels([$model->id]); $other->associateModels([$model->id]);
        Livewire::test(ApplicationDictionary::class, ['applicationId' => $app->id])->call('openDefinition', $model->id)
            ->set('newLanguage', true)->set('languageName', 'C')->set('definitionText', 'Representación C')
            ->call('saveDefinition')->assertHasErrors('definitionText');
        $this->assertSame(0, $model->definitions()->count());
        $this->assertDatabaseMissing('languages', ['name' => 'C']);
        $this->put(route('admin.applications.update', $app), ['name' => $app->name])->assertRedirect();
        $this->assertSame('Dato anterior', $app->fresh()->execution_requirements);
        $this->get(route('admin.applications.edit', $app))->assertOk()->assertDontSee('Requisitos de ejecución');
    }

    public function test_phase1_inverse_relations_share_record_and_other_app_cannot_modify_it(): void
    {
        $app = $this->application();
        $a = $this->owner->models()->create(['name' => 'Animal', 'description' => 'General']);
        $b = $this->owner->models()->create(['name' => 'Perro', 'description' => 'Específico']);
        $app->associateModels([$a->id, $b->id]);
        Livewire::test(ApplicationDictionary::class, ['applicationId' => $app->id])->call('openRelation', $a->id)
            ->set('relationTargetId', $b->id)->set('relationType', 'broader')->call('saveRelation')->assertHasNoErrors();
        Livewire::test(ApplicationDictionary::class, ['applicationId' => $app->id])->call('openRelation', $b->id)
            ->set('relationTargetId', $a->id)->set('relationType', 'specific')->call('saveRelation')->assertHasNoErrors()->assertSee('Más específico que');
        $this->assertDatabaseCount('application_model_relations', 1);
        $relation = \App\Models\ApplicationModelRelation::first();
        $other = $this->owner->applications()->create(['name' => 'Otra', 'system_id' => $this->system->id]);
        $other->associateModels([$a->id, $b->id]);
        Livewire::test(ApplicationDictionary::class, ['applicationId' => $other->id])->call('deactivateRelation', $relation->id)->assertStatus(404);
        $this->assertTrue($relation->fresh()->is_active);
    }

    public function test_application_naming_preview_and_generated_description_are_enforced_on_server(): void
    {
        $this->post(route('admin.applications.store'), ['name' => 'JFRV.Application', 'description' => 'No usar este texto'])->assertRedirect();
        $app = Application::first();
        $this->assertSame('JFRV.MeWo', $app->name);
        $this->assertStringContainsString('usuario Sistema de prueba', $app->description);
        $this->assertStringNotContainsString('No usar', $app->description);
        $this->get(route('admin.applications.edit', $app))->assertOk()->assertSee('Nombre en el sistema')->assertSee('application-naming.js');
        $this->post(route('admin.applications.store'), ['name' => 'JFRV.MeWo'])->assertSessionHasErrors('name');
        $this->put(route('admin.applications.update', $app), ['name' => 'PepeSky.Application'])->assertRedirect();
        $this->assertSame('PepeSky.MeWo', $app->fresh()->name);
        $this->assertSame('Solución informática al problema de comunicación del usuario PepeSky, creada bajo las normas del mecanismo de trabajo MeWo en el sistema JFRV.', $app->fresh()->description);
    }
}
