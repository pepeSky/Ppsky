<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\IdentityController;
use App\Http\Controllers\Admin\ActorController;
use App\Http\Controllers\Admin\InteractionController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\Administration\PlanningController;
use App\Http\Controllers\Admin\Administration\GoalController;
use App\Http\Controllers\Admin\Administration\ObjetiveController;
use App\Http\Controllers\Admin\EntityController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\IdeaController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\Modules\StudyController;
use App\Http\Controllers\Admin\Modules\BlogController;
use App\Http\Controllers\Admin\Modules\DevelopmentController;
use App\Http\Controllers\Admin\Modules\ManagementController;
use App\Http\Controllers\Admin\Modules\AccountingController;
use App\Http\Controllers\Admin\Modules\Accounting\EconomicFactController;

use App\Http\Controllers\Admin\Modules\MantenanceController;
use App\Http\Controllers\Admin\Modules\HealtController;
use App\Http\Controllers\Admin\Modules\StoreController;
use App\Http\Controllers\Admin\Modules\WarehouseController;
use App\Http\Controllers\Admin\Modules\DesignController;

use App\Http\Controllers\Admin\Modules\Resource_Action\ScienceController;
use App\Http\Controllers\Admin\Modules\Resource_Action\ProcessController;
use App\Http\Controllers\Admin\Modules\Resource_Interaction\BusinessController;
use App\Http\Controllers\Admin\Modules\Resource_Interaction\ElementaryController;
use App\Http\Controllers\Admin\Modules\Resource_Interaction\OperativeController;
use App\Http\Controllers\Admin\Modules\Resource_Interaction\ServiceController;
use App\Http\Controllers\Admin\Modules\Resource_Interaction\SupportController;

use App\Http\Controllers\Admin\Modules\Study\SubjectController;

use App\Http\Controllers\Admin\Modules\Development\TaskController;

use App\Http\Controllers\Admin\SyQuAc\UfController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register administration routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [AdminController::class, 'index'])->name('admin.home');

Route::get('/planning', [PlanningController::class, 'index'])
    ->name('admin.planning.index');

Route::get('/planning/{plan}', [PlanningController::class, 'show'])
    ->name('admin.planning.show');

Route::get('/planning/{plan}/edit', [PlanningController::class, 'edit'])
    ->name('admin.planning.edit');

Route::put('/planning/{plan}', [PlanningController::class, 'update'])
    ->name('admin.planning.update');

Route::get('/planning/{plan}/goals/create', [GoalController::class, 'create'])
    ->name('admin.planning.goals.create');

Route::post('/planning/{plan}/goals', [GoalController::class, 'store'])
    ->name('admin.planning.goals.store');

Route::get('/planning/{plan}/goals/{goal}/edit', [GoalController::class, 'edit'])
    ->name('admin.planning.goals.edit');

Route::put('/planning/{plan}/goals/{goal}', [GoalController::class, 'update'])
    ->name('admin.planning.goals.update');

Route::delete('/planning/{plan}/goals/{goal}', [GoalController::class, 'destroy'])
    ->name('admin.planning.goals.destroy');

Route::get('/planning/{plan}/objetives/create', [ObjetiveController::class, 'create'])
    ->name('admin.planning.objetives.create');

Route::post('/planning/{plan}/objetives', [ObjetiveController::class, 'store'])
    ->name('admin.planning.objetives.store');

Route::get('/planning/{plan}/objetives/{objetive}/edit', [ObjetiveController::class, 'edit'])
    ->name('admin.planning.objetives.edit');

Route::put('/planning/{plan}/objetives/{objetive}', [ObjetiveController::class, 'update'])
    ->name('admin.planning.objetives.update');

Route::delete('/planning/{plan}/objetives/{objetive}', [ObjetiveController::class, 'destroy'])
    ->name('admin.planning.objetives.destroy');

Route::get('/syquac/uf', [UfController::class, 'index'])->name('admin.syquac.uf.index');
Route::get('/syquac/uf/{uf}', [UfController::class, 'show'])->name('admin.syquac.uf.show');
Route::post('/syquac/uf/sincronizar',[UfController::class,'sincronizar'])->name('admin.syquac.uf.sincronizar');
Route::post('/system/active', [SystemController::class, 'active'])->name('admin.system.active');


Route::resource('users', UserController::class)->names('admin.users');
Route::resource('roles', RoleController::class)->names('admin.roles');
Route::get('applications/{application}/dictionary', [ApplicationController::class, 'dictionary'])->name('admin.applications.dictionary');
Route::post('applications/{application}/models', [ApplicationController::class, 'storeModel'])->name('admin.applications.models.store');
Route::resource('applications', ApplicationController::class)->names('admin.applications');
Route::resource('units', UnitController::class)->names('admin.units');
Route::resource('categories', CategoryController::class)->names('admin.categories');
Route::resource('tags', TagController::class)->names('admin.tags');
Route::resource('documents', DocumentController::class)->names('admin.documents');

Route::resource('entities', EntityController::class)->names('admin.entities');
Route::resource('activities', ActivityController::class)->names('admin.activities');
Route::resource('posts', PostController::class)->names('admin.posts');
Route::resource('ideas', IdeaController::class)->names('admin.ideas');
Route::resource('interactions', InteractionController::class)->names('admin.interactions');
Route::resource('identities', IdentityController::class)->names('admin.identities');
Route::resource('modules', ModuleController::class)->names('admin.modules');
Route::resource('studies', StudyController::class )->names('admin.studies');
Route::resource('blogs', BlogController::class )->names('admin.blogs');
Route::resource('developments', DevelopmentController::class)->names('admin.developments');
Route::resource('managements', ManagementController::class )->names('admin.managements');
Route::resource('accountings', AccountingController::class)->names('admin.accountings');
Route::resource('mantenances', MantenanceController::class)->names('admin.mantenances');
Route::resource('healts', HealtController::class)->names('admin.healts');
Route::resource('stores', StoreController::class)->names('admin.stores');
Route::resource('warehouses', WarehouseController::class)->names('admin.warehouses');
Route::resource('designs', DesignController::class)->names('admin.designs');



Route::get('actors/import', [ActorController::class, 'importForm'])->name('admin.actors.import');
Route::post('actors/import', [ActorController::class, 'importStore'])->name('admin.actors.import.store');
Route::resource('actors', ActorController::class)->names('admin.actors');

/*
|--------------------------------------------------------------------------
| Resources Action
|--------------------------------------------------------------------------
*/

Route::resource('sciences', ScienceController::class)->names('admin.sciences');
Route::resource('processes', ProcessController::class)->names('admin.processes');

/*
|--------------------------------------------------------------------------
| Resources Interaction
|--------------------------------------------------------------------------
*/

Route::resource('business', BusinessController::class)->names('admin.business');
Route::resource('services', ServiceController::class)->names('admin.services');
Route::resource('supports', SupportController::class)->names('admin.supports');
Route::resource('operatives', OperativeController::class)->names('admin.operatives');
Route::resource('elementaries', ElementaryController::class)->names('admin.elementaries');


/*
|--------------------------------------------------------------------------
| Developments
|--------------------------------------------------------------------------
*/

Route::resource('task', TaskController::class)->names('admin.tasks');

/*
|--------------------------------------------------------------------------
| Studies
|--------------------------------------------------------------------------
*/

Route::resource('subjects', SubjectController::class)->names('admin.subjects');

/*
|--------------------------------------------------------------------------
| Management
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Accounting
|--------------------------------------------------------------------------
*/

Route::resource('economic-facts', EconomicFactController::class)->names('admin.economic-facts');

/*
|--------------------------------------------------------------------------
| Maintenance
|--------------------------------------------------------------------------
*/


// MeWo: shared operational catalogue and project-scoped executions.
Route::get('mewo/contexts', [\App\Http\Controllers\Admin\MeWo\ContextController::class, 'index'])->name('admin.mewo.contexts.index');
Route::resource('mewo/processes', \App\Http\Controllers\Admin\MeWo\ProcessController::class)->parameters(['processes' => 'process'])->only(['index', 'create', 'store', 'edit', 'update'])->names('admin.mewo.processes');
Route::resource('activities.tasks', \App\Http\Controllers\Admin\MeWo\TaskController::class)->only(['create', 'store', 'edit', 'update', 'destroy'])->names('admin.activities.tasks');


Route::resource('binnacles', \App\Http\Controllers\Admin\BinnacleController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update'])->names('admin.binnacles');
Route::get('binnacle-models/create', [\App\Http\Controllers\Admin\BinnacleModelController::class, 'create'])->name('admin.binnacle-models.create');
Route::post('binnacle-models', [\App\Http\Controllers\Admin\BinnacleModelController::class, 'store'])->name('admin.binnacle-models.store');

Route::post('binnacle/day/activities/{activity}/program', [\App\Http\Controllers\Admin\DailyBinnacleController::class, 'program'])->name('admin.binnacle.program');
Route::post('binnacle/day/time', [\App\Http\Controllers\Admin\DailyBinnacleController::class, 'time'])->name('admin.binnacle.time');

// System-scoped actor classifications, relationships and delegated access.
Route::put('actors/{actor}/links', [ActorController::class, 'links'])->name('admin.actors.links');
Route::get('actor-access', [\App\Http\Controllers\Admin\SystemAccessController::class, 'index'])->name('admin.actor-access.index');
Route::post('actor-access', [\App\Http\Controllers\Admin\SystemAccessController::class, 'store'])->name('admin.actor-access.store');
Route::put('actor-access/{role}', [\App\Http\Controllers\Admin\SystemAccessController::class, 'update'])->name('admin.actor-access.update');

Route::post('actors/{actor}/classifications', [ActorController::class, 'classificationStore'])->name('admin.actors.classifications.store');
Route::put('actor-classifications/{classification}/links', [ActorController::class, 'classificationLinks'])->name('admin.actors.classifications.links');
