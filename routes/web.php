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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/pokemons',[App\Http\Controllers\PokemonController::class, 'index']);
Route::put('/pokemon/{id}',[App\Http\Controllers\PokemonController::class, 'putPokemon']);

Route::get('/pokemon_data',[App\Http\Controllers\PokemonController::class, 'postPokemonData']);
