@extends('layouts.main')

@section('title', 'Painel do Artesão - Portal do Artesão')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Painel do Artesão</h2>
            <p class="text-muted mb-0">Bem-vindo, <strong>{{ $artesao->Nome }}</strong></p>
        </div>
        <form action="/logout" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger">Sair</button>
        </form>
    </div>

    @if(session('msg'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('msg') }}
        </div>
    @endif

    <!-- MINHAS CANDIDATURAS -->
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0 fs-5">Minhas Candidaturas em Eventos</h4>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Evento</th>
                        <th>Data</th>
                        <th>Local</th>
                        <th class="text-center">Status da Candidatura</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($artesao->eventos as $inscricao)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $inscricao->Nome }}</td>
                            <td>{{ $inscricao->Dia ? date('d/m/Y H:i', strtotime($inscricao->Dia)) : 'A definir' }}</td>
                            <td>{{ $inscricao->Localizacao ?? 'Não informado' }}</td>
                            <td class="text-center">
                                @php
                                    $status = $inscricao->pivot->StatusDaCandidatura ?? 'Inscrito';
                                    $badgeClass = match($status) {
                                        'Aprovado' => 'bg-success',
                                        'Recusado' => 'bg-danger',
                                        default    => 'bg-warning text-dark'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} px-3 py-2">
                                    {{ $status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                Você ainda não se candidatou a nenhum evento.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- EVENTOS DISPONÍVEIS -->
    @php
        $eventosInscritosIds = $artesao->eventos->pluck('ID_Evento')->toArray();
    @endphp

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0 fs-5">Eventos Disponíveis para Candidatura</h4>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Nome do Evento</th>
                        <th>Data</th>
                        <th>Vagas</th>
                        <th>Local</th>
                        <th class="text-center pe-4">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($eventosDisponiveis as $evento)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $evento->Nome }}</td>
                            <td>{{ $evento->Dia ? date('d/m/Y H:i', strtotime($evento->Dia)) : 'A definir' }}</td>
                            <td>{{ $evento->Vagas ?? 'Ilimitadas' }}</td>
                            <td>{{ $evento->Localizacao ?? 'Não informado' }}</td>
                            <td class="text-center pe-4">
                                @if(in_array($evento->ID_Evento, $eventosInscritosIds))
                                    <span class="badge bg-secondary">Já Candidatado</span>
                                @else
                                    <form action="/artesao/candidatar/{{ $evento->ID_Evento }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            Candidatar-se
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Nenhum evento disponível no momento.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection