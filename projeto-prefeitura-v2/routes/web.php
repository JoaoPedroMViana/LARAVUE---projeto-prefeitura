<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests; 
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\ProtocoloController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartamentosController;
use App\Http\Controllers\AcompanhamentoController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PdfController;

Route::get('/', function () {
    return to_route('login');
});

// Rotas para pessoas -----------------------------------------
Route::get('/pessoas', [PessoaController::class, 'index'])->middleware(['auth', 'verified'])->name('pessoas');

Route::get('/pessoas/cadastro', [PessoaController::class, 'create'])->middleware(['auth'])->name('pessoas.cadastro');

Route::post('/pessoas/store', [PessoaController::class, 'store'])->middleware(['auth'])->middleware([HandlePrecognitiveRequests::class])->name('pessoas.store');

Route::get('/pessoas/pesquisar', [PessoaController::class, 'show'])->middleware(['auth'])->name('pessoas.pesquisar');

Route::get('/pessoa/{id}', [PessoaController::class, 'edit'])->middleware(['auth'])->name('pessoa.edit');

Route::put('/pessoa/update', [PessoaController::class, 'update'])->middleware(['auth'])->middleware([HandlePrecognitiveRequests::class])->name('pessoa.update');

Route::delete('/pessoa/delete/{id}', [PessoaController::class, 'destroy'])->middleware(['auth'])->name('pessoa.delete');


// Rotas para protocolos -----------------------------------------
Route::get('/protocolos', [ProtocoloController::class, 'index'])->middleware(['auth'])->name('protocolos');

Route::get('/protocolos/cadastro', [ProtocoloController::class, 'create'])->middleware(['auth'])->name('protocolos.cadastro');

Route::post('/protocolos/store', [ProtocoloController::class, 'store'])->middleware(['auth'])->middleware([HandlePrecognitiveRequests::class])->name('protocolos.store');

Route::get('/protocolos/pesquisar', [ProtocoloController::class, 'show'])->middleware(['auth'])->name('protocolos.pesquisar');

Route::get('/protocolo/{numero}', [ProtocoloController::class, 'edit'])->middleware(['auth'])->name('protocolo.edit');

Route::put('/protocolo/update', [ProtocoloController::class, 'update'])->middleware(['auth'])->middleware([HandlePrecognitiveRequests::class])->name('protocolo.update');

Route::delete('/protocolo/delete/{numero}', [ProtocoloController::class, 'destroy'])->middleware(['auth'])->name('protocolo.delete');


// Rotas para anexos -----------------------------------------
Route::post('/anexos/{numero}', [ProtocoloController::class, 'storeAnexo'])->middleware(['auth'])->middleware([HandlePrecognitiveRequests::class])->name('anexos.store');

Route::delete('/anexo/delete/{id}', [ProtocoloController::class, 'deleteAnexo'])->middleware(['auth'])->name('anexo.delete');

Route::get('/anexo/download/{path}', [ProtocoloController::class, 'downloadAnexo'])->middleware(['auth'])->name('anexo.download');

// Rotas para usuários -----------------------------------------
Route::get('/usuarios', [RegisteredUserController::class, 'index'])->middleware(['auth'])->name('usuarios');

Route::get('/usuarios/cadastro', [RegisteredUserController::class, 'create'])->middleware(['auth'])->name('usuarios.cadastro');

Route::post('/register', [RegisteredUserController::class, 'store'])->middleware([HandlePrecognitiveRequests::class])->middleware(['auth'])->name('usuarios.store');

Route::get('/usuario/{id}', [RegisteredUserController::class, 'edit'])->middleware(['auth'])->name('usuario.edit');

Route::put('/user/update', [RegisteredUserController::class, 'update'])->middleware([HandlePrecognitiveRequests::class])->middleware(['auth'])->name('users.update');

Route::post('/user/desativar/{id}', [RegisteredUserController::class, 'desativar'])->middleware(['auth'])->name('user.desativar');

Route::post('/user/ativar/{id}', [RegisteredUserController::class, 'ativar'])->middleware(['auth'])->name('user.ativar');

Route::put('/user/mudar_senha', [RegisteredUserController::class, 'mudar_senha'])->middleware([HandlePrecognitiveRequests::class])->middleware(['auth'])->name('user.mudar.senha');

// Rotas departamentos -----------------------------------------
Route::get('/departamentos', [DepartamentosController::class, 'index'])->middleware(['auth'])->name('departamentos');

Route::get('/departamentos/cadastro', [DepartamentosController::class, 'create'])->middleware(['auth'])->name('departamentos.cadastro');

Route::post('/departamentos/store', [DepartamentosController::class, 'store'])->middleware([HandlePrecognitiveRequests::class])->middleware(['auth'])->name('departamentos.store');

Route::get('/departamento/{id}', [DepartamentosController::class, 'edit'])->middleware(['auth'])->name('departamento.edit');

Route::put('/departamento/update', [DepartamentosController::class, 'update'])->middleware([HandlePrecognitiveRequests::class])->middleware(['auth'])->name('departamento.update');

// Rotas permissões -----------------------------------------
Route::post('/permissoes/liberar', [DepartamentosController::class, 'liberarPermissao'])->middleware(['auth'])->name('permissoes.liberar');

Route::delete('/permissoes/remover/{id}', [DepartamentosController::class, 'removerPermissao'])->middleware(['auth'])->name('permissoes.remover');

// Rotas acompanhamentos -----------------------------------------
Route::post('/acompanhamento/store', [AcompanhamentoController::class, 'store'])->middleware([HandlePrecognitiveRequests::class])->middleware(['auth'])->name('acompanhamento.store');


// Rotas download PDF -----------------------------------------
Route::get('/download/pdf/', [PdfController::class, 'downloadPdf'])->middleware(['auth'])->name('downloadPdf');
Route::get('/download/pdf/{id}', [PdfController::class, 'downloadPdfIndividual'])->middleware(['auth'])->name('downloadPdfIndividual');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
