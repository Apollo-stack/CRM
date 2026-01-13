<?php
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
Route::middleware(['auth'])->group(function () {
    // Relatórios e Exportação
    Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/clients', [App\Http\Controllers\ReportController::class, 'exportClients'])->name('reports.clients');
    Route::get('/reports/leads', [App\Http\Controllers\ReportController::class, 'exportLeads'])->name('reports.leads');

    // Lixeira (Antes do Resource para não dar conflito com {id})
    Route::get('/clients/trash', [ClientController::class, 'trash'])->name('clients.trash');
    Route::put('/clients/{id}/restore', [ClientController::class, 'restore'])->name('clients.restore');
    Route::delete('/clients/{id}/force', [ClientController::class, 'forceDelete'])->name('clients.force_delete');

    Route::get('/leads/trash', [LeadController::class, 'trash'])->name('leads.trash');
    Route::put('/leads/{id}/restore', [LeadController::class, 'restore'])->name('leads.restore');
    Route::delete('/leads/{id}/force', [LeadController::class, 'forceDelete'])->name('leads.force_delete');

    Route::resource('clients', ClientController::class);
    Route::resource('leads', LeadController::class);
    Route::post('/leads/{id}/notes', [LeadController::class, 'storeNote'])->name('leads.notes.store');
    Route::get('/search', [DashboardController::class, 'search'])->name('global.search');
    Route::get('/clientes/{id}/json', [ClientController::class, 'obterDadosJson']);
    Route::get('/clientes/{id}/endereco', [ClientController::class, 'buscaEndereco']);
});