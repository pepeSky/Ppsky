<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;

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

use App\Http\Controllers\Admin\Resource_Interaction\BusinessController;
use App\Http\Controllers\Admin\Development\TaskController;
use App\Http\Controllers\Admin\Resource_Action\ProcessController;
use App\Http\Controllers\Admin\Resource_Interaction\ElementaryController;
use App\Http\Controllers\Admin\Resource_Interaction\OperativeController;
use App\Http\Controllers\Admin\Resource_Interaction\ServiceController;
use App\Http\Controllers\Admin\Resource_Interaction\SupportController;
use App\Http\Controllers\Admin\Subject\SubjectController;


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

Route::resource('users', UserController::class)->names('admin.users');
Route::resource('roles', RoleController::class)->names('admin.roles');
Route::resource('units', UnitController::class)->names('admin.units');
Route::resource('categories', CategoryController::class)->names('admin.categories');
Route::resource('tags', TagController::class)->names('admin.tags');


Route::resource('entities', EntityController::class)->names('admin.entities');
Route::resource('activities', ActivityController::class)->names('admin.activities');
Route::resource('posts', PostController::class)->names('admin.posts');
Route::resource('ideas', IdeaController::class)->names('admin.ideas');


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

