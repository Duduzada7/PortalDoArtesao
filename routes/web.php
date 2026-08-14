<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdmController;
use App\Http\Controllers\EventController;

Route::view('/', 'welcome');
Route::get('/welcome', [EventController::class,'index']);
Route::get('/admin/eventos/criar', [EventController::class,'create']);
Route::get('/Eventos/list', [EventController::class,'list']);
Route::get('/Eventos/delete', [EventController::class,'delete']);
Route::post('/admin/eventos', [EventController::class, 'store']);
Route::get('/admin/eventos', [EventController::class, 'index']);
Route::delete('/admin/eventos/{id}', [EventController::class, 'destroy']);
Route::view('/artesao', 'Artesaos.list');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

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