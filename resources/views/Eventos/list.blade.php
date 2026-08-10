@extends('layouts.main')

@section('title', 'Listar Eventos - Portal do Artesão')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Listar Eventos</h2>
        <div>
            <a href="/admin/dashboard" class="btn btn-outline-secondary me-2">Voltar ao Painel</a>
            <a href="/admin/eventos/criar" class="btn btn-primary">+ Criar Novo Evento</a>
        </div>
    </div>

    @if(session('msg'))
        <div class="alert alert-success">
            {{ session('msg') }}
        </div>
    @endif

    @if(count($eventos) > 0)
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Classificação</th>
                        <th>Vagas</th>
                        <th>Localização</th>
                        <th>Data / Horário</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($eventos as $evento)
                        <tr>
                            <td>{{ $evento->ID_Evento }}</td>
                            <td class="fw-bold">{{ $evento->Nome }}</td>
                            <td>{{ $evento->Classificacao ?? '-' }}</td>
                            <td>{{ $evento->Vagas ?? '-' }}</td>
                            <td>{{ $evento->Localizacao ?? '-' }}</td>
                            <td>
                                {{ $evento->Dia ? date('d/m/Y H:i', strtotime($evento->Dia)) : '-' }}
                            </td>
                            <td class="text-center">
                                <form action="/admin/eventos/{{ $evento->ID_Evento }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este evento?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info text-center">
            Nenhum evento cadastrado no momento. <a href="/admin/eventos/criar">Clique aqui</a> para criar um.
        </div>
    @endif
</div>
@endsection