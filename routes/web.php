<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AreneController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DresseurController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrganisationController;
use App\Http\Controllers\PokedexController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Site public (mêmes URLs que la version Symfony)
|--------------------------------------------------------------------------
*/
Route::get('/', HomeController::class)->name('home');

Route::get('/recherche', SearchController::class)->name('search');

Route::get('/pokedex/pokemon/{pokemon:numero_pokedex}', [PokedexController::class, 'show'])
    ->whereNumber('pokemon')
    ->name('pokedex.show');
// Anciennes URL par nom (/pokedex/show/Pikachu) : redirection permanente vers l'URL par numéro
Route::get('/pokedex/show/{nom}', [PokedexController::class, 'redirectFromName']);
Route::get('/pokedex/{generation?}', [PokedexController::class, 'index'])
    ->whereNumber('generation')
    ->name('pokedex.index');

Route::get('/dresseurs', [DresseurController::class, 'index'])->name('dresseur.index');
Route::get('/dresseur/{slug}', [DresseurController::class, 'show'])->name('dresseur.show');

Route::get('/arenes', [AreneController::class, 'index'])->name('arene.index');
Route::get('/arene/{slug}', [AreneController::class, 'show'])->name('arene.show');

Route::get('/organisations', [OrganisationController::class, 'index'])->name('organisation.index');
Route::get('/organisation/{slug}', [OrganisationController::class, 'show'])->name('organisation.show');

/*
|--------------------------------------------------------------------------
| Connexion
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/connexion', [LoginController::class, 'create'])->name('login');
    Route::post('/connexion', [LoginController::class, 'store']);
});

Route::post('/deconnexion', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Administration : connexion obligatoire + droit administrateur
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'can:access-admin'])->group(function () {
    Route::get('/admin', Admin\DashboardController::class)->name('admin');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('dresseurs', Admin\DresseurController::class)->except('show');
        Route::resource('teams', Admin\TeamController::class)->except('show');
        Route::resource('arenes', Admin\AreneController::class)->except('show');
        Route::resource('organisations', Admin\OrganisationController::class)->except('show');
        Route::resource('pokemons', Admin\PokemonController::class)->only(['index', 'edit', 'update']);
    });
});
