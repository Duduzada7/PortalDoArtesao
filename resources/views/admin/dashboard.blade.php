@extends('layouts.main')

@section('title', 'Painel Administrativo')

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Painel do Administrador</h1>
        <form action="/logout" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger">Sair</button>
        </form>
    </div>

    <div class="alert alert-info">
        Bem-vindo, <strong>{{ session('user_nome') }}</strong>!
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card text-center p-3">
                <h3>Eventos</h3>
                <p>Gerencie as feiras e encontros.</p>
                <a href="/admin/eventos/criar" class="btn btn-primary">Criar Novo Evento</a>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card text-center p-3">
                <h3>Aprovações</h3>
                <p>Avalie cadastros de artesãos pendentes.</p>
                <a href="#" class="btn btn-warning disabled">Ver Pendentes</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card text-center p-3">
            <h3>Novo Evento</h3>
            <p>Cadastre uma nova feira ou exposição.</p>
            <a href="/admin/eventos/criar" class="btn btn-primary">Criar Novo Evento</a>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card text-center p-3">
            <h3>Lista de Eventos</h3>
            <p>Veja e gerencie todos os eventos cadastrados.</p>
            <a href="/admin/eventos" class="btn btn-secondary">Ver Eventos</a>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card text-center p-3">
            <h3>Aprovações</h3>
            <p>Avalie cadastros de artesãos pendentes.</p>
            <a href="#" class="btn btn-warning disabled">Ver Pendentes</a>
        </div>
    </div>
</div>
@endsection