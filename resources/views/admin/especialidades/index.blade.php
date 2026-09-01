@extends('layouts.main')

@section('title', 'Gerenciar Especialidades - Portal do Artesão')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Gerenciar Especialidades</h2>
                <a href="/admin/dashboard" class="btn btn-outline-secondary">Voltar ao Painel</a>
            </div>

            @if(session('msg'))
                <div class="alert alert-success">{{ session('msg') }}</div>
            @endif

            <!-- Form para nova especialidade -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white fw-bold">Nova Especialidade</div>
                <div class="card-body">
                    <form action="/admin/especialidades" method="POST" class="row g-2">
                        @csrf
                        <div class="col-md-9">
                            <input type="text" name="Nome" class="form-control" placeholder="Ex: Marcenaria, Saboaria, Macramê..." required>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success w-100">+ Adicionar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabela de Especialidades Existentes -->
            <div class="card shadow-sm">
                <div class="card-header bg-light fw-bold">Especialidades Cadastradas</div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Nome da Especialidade</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($especialidades as $esp)
                                <tr>
                                    <td>{{ $esp->ID_Especialidade }}</td>
                                    <td><strong>{{ $esp->Nome }}</strong></td>
                                    <td class="text-end">
                                        <form action="/admin/especialidades/{{ $esp->ID_Especialidade }}" method="POST" onsubmit="return confirm('Tem certeza que deseja remover esta especialidade?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-3 text-muted">Nenhuma especialidade cadastrada até o momento.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection