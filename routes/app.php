<?php

use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\PostController;
use App\Http\Controllers\Home\ProjectDossierController;
use App\Http\Controllers\Home\PropositionController;
use App\Http\Controllers\Admin\SystemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Home Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('/', [HomeController::class, 'index'])->name('app.home');
Route::post('/system/active', [SystemController::class, 'active'])->name('app.system.active');
Route::get('/home', [PostController::class,'index'])->name('home');

Route::get('posts/{post}', [PostController::class,'show'])->name('posts.show');
Route::get('category/{category}', [PostController::class,'category'])->name('posts.category');
Route::get('tag/{tag}', [PostController::class,'tags'])->name('posts.tag');
Route::get('/projects/{project}/dossier', [ProjectDossierController::class, 'show'])
    ->name('projects.dossier.show');
Route::get('/propositions', [PropositionController::class, 'index'])
    ->name('propositions.index');

Route::get('/propositions/create', [PropositionController::class, 'create'])
    ->name('propositions.create');

Route::post('/propositions', [PropositionController::class, 'store'])
    ->name('propositions.store');
