<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdmController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ArtesaoController;

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

//fluxo de artesaos
Route::get('/artesao', [ArtesaoController::class, 'index']);
Route::get('/artesao/cadastrar', [ArtesaoController::class, 'create']);

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