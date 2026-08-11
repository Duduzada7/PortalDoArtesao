<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

//Route::get('/', function(){return view('welcome');}); 
Route::view('/', 'welcome');//landing page
Route::get('/welcome', [EventController::class,'index']);
Route::get('/admin/eventos/criar', [EventController::class,'create']);
Route::get('/Eventos/list', [EventController::class,'list']);
Route::get('/Eventos/delete', [EventController::class,'delete']);
Route::post('/admin/eventos', [EventController::class, 'store']);
Route::get('/admin/eventos', [EventController::class, 'index']);
Route::delete('/admin/eventos/{id}', [EventController::class, 'destroy']);
//Route::get('/artesao', function(){return view('Artesaos.list');});
Route::view('/artesao', 'Artesaos.list');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Painel do Admin (Proteção simples por checagem de sessão)
Route::get('/admin/dashboard', function () {
    if (session('user_type') !== 'adm') {
        return redirect('/login')->with('error', 'Acesso restrito a Administradores.');
    }
    return view('admin.dashboard');
});