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
Route::middleware(['auth'])->group(function () {
    Route::resource('clients', ClientController::class);
    Route::resource('leads', LeadController::class);
    Route::post('/leads/{id}/notes', [LeadController::class, 'storeNote'])->name('leads.notes.store');
    Route::get('/search', [DashboardController::class, 'search'])->name('global.search');
    Route::get('/clientes/{id}/json', [ClientController::class, 'obterDadosJson']);
    Route::get('/clientes/{id}/endereco', [ClientController::class, 'buscaEndereco']);
});