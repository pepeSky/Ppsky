<?php

use App\Http\Controllers\Home\StudyController;
use App\Http\Controllers\Home\BusinessController;
use App\Http\Controllers\Welcome\DevelopmentController;
use App\Http\Controllers\Welcome\ModuleController;
use App\Http\Controllers\welcome\SubjectController;
use App\Http\Controllers\Welcome\WelcomeController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', WelcomeController::class)->name('welcome');

Route::get('aplications', [ModuleController::class, 'index'])->name('Aplications');

Route::get('modules/{module}',function(){
    return "Aquí se mostrará la información del modulo";
})->name('module.show');

Route::get('developments', [DevelopmentController::class, 'index'] )->name('developments');

Route::get('developments/{development}', [DevelopmentController::class,'show'])->name('development.show');

Route::get('subjects', [SubjectController::class, 'index'])->name('subjects');

Route::get('us-me', function(){
    return 'hola contactame';
})->name('us-me');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/studies', [StudyController::class, 'index'])->name('studies.index');

    Route::get('/business', [BusinessController::class, 'index'])->name('business.index');

    Route::get('/modules', function(){
        return 'vista de modulos';
    })->name('modules.index');

});
