@extends('layouts.main')

@section('title', 'Painel Administrativo - Portal do Artesão')

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Painel do Administrador</h1>
        <form action="/logout" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger">Sair</button>
        </form>
    </div>

    @if(session('user_nome'))
        <div class="alert alert-info">
            Bem-vindo, <strong>{{ session('user_nome') }}</strong>!
        </div>
    @endif

    <div class="row">
        <!-- Card 1: Criar Evento -->
        <div class="col-md-4 mb-3">
            <div class="card text-center p-3 shadow-sm h-100">
                <h3>Novo Evento</h3>
                <p class="text-muted">Cadastre uma nova feira ou exposição.</p>
                <div class="mt-auto">
                    <a href="/admin/eventos/criar" class="btn btn-primary w-100">Criar Novo Evento</a>
                </div>
            </div>
        </div>

        <!-- Card 2: Listar Eventos -->
        <div class="col-md-4 mb-3">
            <div class="card text-center p-3 shadow-sm h-100">
                <h3>Lista de Eventos</h3>
                <p class="text-muted">Veja e gerencie todos os eventos cadastrados.</p>
                <div class="mt-auto">
                    <a href="/admin/eventos" class="btn btn-secondary w-100">Ver Eventos</a>
                </div>
            </div>
        </div>

        <!-- Card 3: Aprovações -->
        <div class="col-md-4 mb-3">
            <div class="card text-center p-3 shadow-sm h-100">
                <h3>Aprovações</h3>
                <p class="text-muted">Avalie cadastros de artesãos pendentes.</p>
                <div class="mt-auto">
                    <a href="#" class="btn btn-warning w-100 disabled">Ver Pendentes</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
    <div class="card text-center p-3 shadow-sm h-100">
        <h3>Administradores</h3>
        <p class="text-muted">Cadastre outros gestores da prefeitura.</p>
        <div class="mt-auto">
            <a href="/admin/gerenciar-adms" class="btn btn-info text-white w-100">Gerenciar ADMs</a>
        </div>
    </div>
</div>
</div>
@endsection