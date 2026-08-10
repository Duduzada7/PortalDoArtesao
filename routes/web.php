<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::get('/welcome', [EventController::class,'index']);
Route::get('/Eventos/create', [EventController::class,'create']);
Route::get('/Eventos/list', [EventController::class,'list']);
Route::get('/Eventos/delete', [EventController::class,'delete']);