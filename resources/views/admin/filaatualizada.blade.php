@extends('layouts.main')

@section('title', 'Fila de Prioridade - Portal do Artesão')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Fila de Prioridade dos Artesãos</h2>
        <a href="/admin/dashboard" class="btn btn-outline-secondary">Voltar ao Painel</a>
    </div>

    @if(session('msg'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('msg') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Ordem de Credenciamento para Eventos</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 80px;">Posição</th>
                            <th>Artesão</th>
                            <th>Bairro</th>
                            <th>Nível</th>
                            <th>Última Participação</th>
                            <th class="text-end pe-4">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($artesaos as $artesao)
                            <tr>
                                <td class="ps-4 fw-bold">
                                    <span class="badge rounded-circle p-2 {{ $artesao->posicao_fila == 1 ? 'bg-danger' : 'bg-secondary' }}">
                                        {{ $artesao->posicao_fila }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $artesao->Nome }}</div>
                                    <small class="text-muted">{{ $artesao->Rua }}{{ $artesao->Numero ? ', ' . $artesao->Numero : '' }}</small>
                                </td>
                                <td>{{ $artesao->Bairro ?? 'Não informado' }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border p-2 fw-bold">
                                        {{ $artesao->Nivel ?? 'Geral' }}
                                    </span>
                                </td>
                                <td>
                                    @if(is_null($artesao->DataUltimaParticipacao))
                                        <span class="badge bg-success">Nunca participou (Prioridade)</span>
                                    @else
                                        {{ \Carbon\Carbon::parse($artesao->DataUltimaParticipacao)->format('d/m/Y H:i') }}
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <form action="/admin/fila/mover-final/{{ $artesao->ID_Artesao }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary" title="Mover para o final da fila">
                                            &darr; Mover pro fim
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Nenhum artesão encontrado na fila.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection