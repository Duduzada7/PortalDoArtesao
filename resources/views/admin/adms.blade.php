@extends('layouts.main')

@section('title', 'Gerenciar Administradores - Portal do Artesão')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- ALERTAS DE SUCESSO / ERRO -->
            @if(session('msg'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('msg') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- CARD DE CADASTRO -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Cadastrar Novo Administrador</h4>
                </div>
                <div class="card-body">
                    <form action="/admin/gerenciar-adms" method="POST">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label for="Nome" class="form-label fw-bold">Nome Completo: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('Nome') is-invalid @enderror" id="Nome" name="Nome" value="{{ old('Nome') }}" placeholder="Ex: Maria da Silva" required>
                            @error('Nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="Email" class="form-label fw-bold">E-mail: <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('Email') is-invalid @enderror" id="Email" name="Email" value="{{ old('Email') }}" placeholder="admin@prefeitura.gov.br" required>
                            @error('Email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="Senha" class="form-label fw-bold">Senha de Acesso: <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('Senha') is-invalid @enderror" id="Senha" name="Senha" placeholder="Mínimo 6 caracteres" required>
                            @error('Senha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="/admin/dashboard" class="btn btn-outline-secondary">Voltar ao Painel</a>
                            <button type="submit" class="btn btn-primary fw-bold">Cadastrar ADM</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TABELA DE ADMS CADASTRADOS -->
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Administradores Cadastrados</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>E-mail</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($adms as $adm)
                                    <tr>
                                        <td>{{ $adm->ID_Adm ?? $adm->id }}</td>
                                        <td>{{ $adm->Nome }}</td>
                                        <td>{{ $adm->Email }}</td>
                                        <td class="text-end">
                                            @if($adm->ID_Adm != session('user_id') && $adm->id != session('user_id'))
                                                <form action="/admin/gerenciar-adms/{{ $adm->ID_Adm ?? $adm->id }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja remover este administrador?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">Remover</button>
                                                </form>
                                            @else
                                                <span class="badge bg-secondary">Você</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">Nenhum administrador cadastrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection