<?php

use App\Http\Controllers\Home\PostController;
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



Route::get('/home', [PostController::class,'index'])->name('home');

Route::get('posts/{post}', [PostController::class,'show'])->name('posts.show');
Route::get('category/{category}', [PostController::class,'category'])->name('posts.category');
Route::get('tag/{tag}', [PostController::class,'tags'])->name('posts.tag');

