<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdmController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ArtesaoController;
use App\Http\Controllers\FilaController;
use App\Http\Controllers\AprovacaoController;
use App\Http\Controllers\EspecialidadeController;

// Páginas Públicas
Route::view('/', 'welcome');
Route::get('/welcome', [EventController::class, 'index']);
Route::get('/Eventos/list', [EventController::class, 'index'])->name('eventos.list');

// Autenticação
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Fluxo CRUD de artesãos
Route::get('/artesao', [ArtesaoController::class, 'index']);
Route::get('/artesao/cadastrar', [ArtesaoController::class, 'create']);
Route::post('/artesao/cadastrar', [ArtesaoController::class, 'store']);
Route::get('/artesao/editar/{id}', [ArtesaoController::class, 'edit']);
Route::put('/artesao/{id}', [ArtesaoController::class, 'update']);
Route::delete('/artesao/{id}', [ArtesaoController::class, 'destroy']);

// Área logada do artesão
Route::get('/artesao/dashboard', [ArtesaoController::class, 'dashboard']);
Route::post('/artesao/candidatar/{id}', [ArtesaoController::class, 'candidatarEvento']);

// --------------------------------------------------------------------------
// PAINEL ADMINISTRATIVO (Protegido por verificação de sessão)
// --------------------------------------------------------------------------
Route::prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        if (session('user_type') !== 'adm') {
            return redirect('/login')->with('error', 'Acesso restrito a Administradores.');
        }
        return view('admin.dashboard');
    });

    // Gerenciamento de Especialidades
    Route::get('/especialidades', function () {
        if (session('user_type') !== 'adm') {
            return redirect('/login')->with('error', 'Acesso restrito a Administradores.');
        }
        return app(EspecialidadeController::class)->index();
    })->name('especialidades.index');

    Route::post('/especialidades', [EspecialidadeController::class, 'store'])->name('especialidades.store');
    Route::delete('/especialidades/{id}', [EspecialidadeController::class, 'destroy'])->name('especialidades.destroy');

    // Gerenciamento de Eventos
    Route::get('/eventos', [EventController::class, 'index']);
    Route::get('/eventos/criar', [EventController::class, 'create']);
    Route::post('/eventos', [EventController::class, 'store']);
    Route::get('/eventos/{id}/editar', [EventController::class, 'edit']);
    Route::put('/eventos/{id}', [EventController::class, 'update']);
    Route::delete('/eventos/{id}', [EventController::class, 'destroy']);

    // Gerenciamento de ADM
    Route::get('/gerenciar-adms', [AdmController::class, 'index']);
    Route::post('/gerenciar-adms', [AdmController::class, 'store']);
    Route::delete('/gerenciar-adms/{id}', [AdmController::class, 'destroy']);

    // Gerenciamento da Fila
    Route::get('/fila', [FilaController::class, 'index']);
    Route::post('/fila/mover-final/{id}', [FilaController::class, 'moverParaFinal']);

    // Aprovações
    Route::get('/aprovacoes', [AprovacaoController::class, 'index']);
    Route::post('/aprovacoes/artesao/{id}/aprovar', [AprovacaoController::class, 'aprovarArtesao']);
    Route::post('/aprovacoes/artesao/{id}/recusar', [AprovacaoController::class, 'recusarArtesao']);
    Route::post('/aprovacoes/evento/{eventoId}/artesao/{artesaoId}/aprovar', [AprovacaoController::class, 'aprovarCandidatura']);
    Route::post('/aprovacoes/evento/{eventoId}/artesao/{artesaoId}/recusar', [AprovacaoController::class, 'recusarCandidatura']);
});