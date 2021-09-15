<?php

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

use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');


Route::get('/dashboard',function(){
    return view('dashboard');
})->name('dashboard');

Route::get('/posts', function(){
    return view('post.index');
})->name('posts.index');

Route::get('/posts/create', function(){
    return view('post.create');
})->name('posts.create');

Route::get('/posts/{post}', function($post){
    return view('post.show', compact('post'));
})->name('posts.show');

Route::get('/posts/{post}/edit', function($post){
    return view('post.edit', compact('post'));
})->name('posts.edit');
