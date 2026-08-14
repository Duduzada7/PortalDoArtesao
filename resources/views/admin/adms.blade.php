@extends('layouts.main')

@section('title', 'Gerenciar Administradores - Portal do Artesão')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gerenciar Administradores</h2>
        <a href="/admin/dashboard" class="btn btn-outline-secondary">Voltar ao Painel</a>
    </div>

    @if(session('msg'))
        <div class="alert alert-success">
            {{ session('msg') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <!-- FORMULÁRIO DE CADASTRO -->
        <div class="col-md-5 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0 fs-5">Cadastrar Novo ADM</h4>
                </div>
                <div class="card-body">
                    <form action="/admin/gerenciar-adms" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="Nome" class="form-label fw-bold">Nome Completo: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="Nome" name="Nome" required placeholder="Ex: Maria Silva">
                        </div>

                        <div class="form-group mb-3">
                            <label for="Email" class="form-label fw-bold">E-mail de Acesso: <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="Email" name="Email" required placeholder="Ex: maria.silva@prefeitura.gov.br">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Cadastrar ADM</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- LISTAGEM DE ADMS EXISTENTES -->
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0 fs-5">Administradores Cadastrados</h4>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th class="text-center">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($adms as $adm)
                                <tr>
                                    <td>{{ $adm->Id_ADM }}</td>
                                    <td class="fw-bold">{{ $adm->Nome }}</td>
                                    <td>{{ $adm->Email }}</td>
                                    <td class="text-center">
                                        <form action="/admin/gerenciar-adms/{{ $adm->Id_ADM }}" method="POST" onsubmit="return confirm('Tem certeza que deseja remover este Administrador?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Remover</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection