<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\ShowPosts;
use App\Http\Controllers\CurrentTeamController;
use App\Http\Controllers\TeamController;

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

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard',ShowPosts::class)->name('dashboard');

// Rutas para la gestión de equipos
Route::put('/current-team', [CurrentTeamController::class, 'update'])->middleware(['auth:sanctum', 'verified'])->name('current-team.update');

// Rutas adicionales para equipos (teams)
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');
});

