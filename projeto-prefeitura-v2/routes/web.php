<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests; 
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PessoaController;

Route::get('/', function () {
    return to_route('login');
});

// Rotas para pessoas:
Route::get('/pessoas', [PessoaController::class, 'index'])->middleware(['auth', 'verified'])->name('pessoas');

Route::get('/pessoas/pesquisar', [PessoaController::class, 'show'])->middleware(['auth'])->name('pessoas.pesquisar');

Route::get('/pessoa/{id}', [PessoaController::class, 'edit'])->middleware(['auth'])->name('pessoa.edit');

Route::put('/pessoa/update', [PessoaController::class, 'update'])->middleware(['auth'])->middleware([HandlePrecognitiveRequests::class])->name('pessoa.update');

Route::delete('/pessoa/delete/{id}', [PessoaController::class, 'destroy'])->middleware(['auth'])->name('pessoa.delete');


// Rotas para protocolos:
Route::get('/protocolos', function() {
    return Inertia::render('Protocolos');
})->middleware(['auth'])->name('protocolos');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
