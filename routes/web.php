<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdmController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ArtesaoController;
use App\Http\Controllers\FilaController;
use App\Http\Controllers\AprovacaoController;

Route::view('/', 'welcome');
Route::get('/welcome', [EventController::class,'index']);
Route::get('/admin/eventos/criar', [EventController::class,'create']);
Route::get('/Eventos/list', [EventController::class,'index'])->name('eventos.list');
Route::get('/Eventos/delete', [EventController::class,'delete']);
Route::post('/admin/eventos', [EventController::class, 'store']);
Route::get('/admin/eventos', [EventController::class, 'index']);
Route::delete('/admin/eventos/{id}', [EventController::class, 'destroy']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

//fluxo CRUD de artesaos
Route::get('/artesao', [ArtesaoController::class, 'index']);
Route::get('/artesao/cadastrar', [ArtesaoController::class, 'create']);
Route::post('/artesao/cadastrar', [ArtesaoController::class, 'store']);
Route::get('/artesao/editar/{id}', [ArtesaoController::class, 'edit']);
Route::put('/artesao/{id}', [ArtesaoController::class, 'update']);
Route::delete('/artesao/{id}', [ArtesaoController::class, 'destroy']);

//area logada do artesao
Route::get('/artesao/dashboard', [ArtesaoController::class, 'dashboard']);
Route::post('/artesao/candidatar/{id}', [ArtesaoController::class, 'candidatarEvento']);
// Painel do Admin
Route::get('/admin/dashboard', function () {
    if (session('user_type') !== 'adm') {
        return redirect('/login')->with('error', 'Acesso restrito a Administradores.');
    }
    return view('admin.dashboard');
});

// Gerenciamento de ADM
Route::get('/admin/gerenciar-adms', [AdmController::class, 'index']);
Route::post('/admin/gerenciar-adms', [AdmController::class, 'store']);
Route::delete('/admin/gerenciar-adms/{id}', [AdmController::class, 'destroy']);

// Gerenciamento da fila
Route::get('/admin/fila', [FilaController::class, 'index']);
Route::post('/admin/fila/mover-final/{id}', [FilaController::class, 'moverParaFinal']);
// Rotas de Aprovação do ADM
Route::get('/admin/aprovacoes', [AprovacaoController::class, 'index']);

// Aprovação / Recusa de Cadastro
Route::post('/admin/aprovacoes/artesao/{id}/aprovar', [AprovacaoController::class, 'aprovarArtesao']);
Route::post('/admin/aprovacoes/artesao/{id}/recusar', [AprovacaoController::class, 'recusarArtesao']);

// Aprovação / Recusa de Candidatura em Evento
Route::post('/admin/aprovacoes/evento/{eventoId}/artesao/{artesaoId}/aprovar', [AprovacaoController::class, 'aprovarCandidatura']);
Route::post('/admin/aprovacoes/evento/{eventoId}/artesao/{artesaoId}/recusar', [AprovacaoController::class, 'recusarCandidatura']);