<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(){
        return view('welcome');
    }

    public function create(){
        return view('Eventos.create');
    }
    public function delete(){
        return view('Eventos.delete');
    }
    public function list(){
        return view('Eventos.list');
    }
}
