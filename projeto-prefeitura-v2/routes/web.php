<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests; 
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\ProtocoloController;

Route::get('/', function () {
    return to_route('login');
});

// Rotas para pessoas:
Route::get('/pessoas', [PessoaController::class, 'index'])->middleware(['auth', 'verified'])->name('pessoas');

Route::get('/pessoas/cadastro', [PessoaController::class, 'create'])->middleware(['auth'])->name('pessoas.cadastro');

Route::post('/pessoas/store', [PessoaController::class, 'store'])->middleware(['auth'])->middleware([HandlePrecognitiveRequests::class])->name('pessoas.store');

Route::get('/pessoas/pesquisar', [PessoaController::class, 'show'])->middleware(['auth'])->name('pessoas.pesquisar');

Route::get('/pessoa/{id}', [PessoaController::class, 'edit'])->middleware(['auth'])->name('pessoa.edit');

Route::put('/pessoa/update', [PessoaController::class, 'update'])->middleware(['auth'])->middleware([HandlePrecognitiveRequests::class])->name('pessoa.update');

Route::delete('/pessoa/delete/{id}', [PessoaController::class, 'destroy'])->middleware(['auth'])->name('pessoa.delete');



// Rotas para protocolos:
Route::get('/protocolos', [ProtocoloController::class, 'index'])->middleware(['auth'])->name('protocolos');

Route::get('/protocolos/cadastro', [ProtocoloController::class, 'create'])->middleware(['auth'])->name('protocolos.cadastro');

Route::post('/protocolos/store', [ProtocoloController::class, 'store'])->middleware(['auth'])->middleware([HandlePrecognitiveRequests::class])->name('protocolos.store');

Route::get('/protocolo/{numero}', [ProtocoloController::class, 'edit'])->middleware(['auth'])->name('protocolo.edit');

Route::put('/protocolo/update', [ProtocoloController::class, 'update'])->middleware(['auth'])->middleware([HandlePrecognitiveRequests::class])->name('protocolo.update');

Route::delete('/protocolo/delete/{numero}', [ProtocoloController::class, 'destroy'])->middleware(['auth'])->name('protocolo.delete');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
