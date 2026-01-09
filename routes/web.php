<?php
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
Route::resource('clients', ClientController::class);
Route::resource('leads', LeadController::class)->middleware(['auth']);
Route::post('/leads/{id}/notes', [App\Http\Controllers\LeadController::class, 'storeNote'])->name('leads.notes.store');
Route::get('/search', [App\Http\Controllers\DashboardController::class, 'search'])->name('global.search');
// Rota para buscar dados do cliente via AJAX
Route::get('/clientes/{id}/json', [App\Http\Controllers\ClientController::class, 'obterDadosJson']);
Route::get('/clientes/{id}/endereco', [App\Http\Controllers\ClientController::class, 'buscaEndereco']);
// Busca global
Route::get('/search', [DashboardController::class, 'search'])->name('global.search');