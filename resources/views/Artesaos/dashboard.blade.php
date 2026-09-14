@extends('layouts.main')

@section('title', 'Painel do Artesão - Portal do Artesão')

@section('content')
<div class="container my-5">
    
    <!-- CABEÇALHO COM BOAS-VINDAS E LOGOUT -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Olá, {{ $artesao->Nome }}!</h2>
            <p class="text-muted mb-0">Bem-vindo ao seu painel de acompanhamento e inscrições.</p>
        </div>
        <form action="/logout" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger">Sair</button>
        </form>
    </div>

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

    <div class="row g-4 mb-5">
        <!-- CARD DE INFORMAÇÕES PESSOAIS -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-header bg-primary text-white fw-bold">
                    Minhas Informações & Especialidades
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>E-mail:</strong> {{ $artesao->Email }}</p>
                    <p class="mb-1"><strong>Telefone:</strong> {{ $artesao->Telefone }}</p>
                    <p class="mb-3">
                        <strong>Endereço:</strong> 
                        {{ $artesao->Rua ? $artesao->Rua . ', ' . $artesao->Numero . ' - ' . $artesao->Bairro : 'Não informado' }}
                    </p>

                    <h6 class="fw-bold mb-2">Especialidades Cadastradas:</h6>
                    <div class="d-flex flex-wrap gap-1">
                        @forelse($artesao->especialidades as $esp)
                            <span class="badge bg-light text-dark border">{{ $esp->Nome }}</span>
                        @empty
                            <span class="text-muted small">Nenhuma especialidade vinculada.</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- CARD DE POSIÇÃO NA FILA DE PRIORIDADE -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100 border-0 bg-light">
                <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
                    <h5 class="text-muted mb-2">Sua Posição Atual na Fila</h5>
                    <div class="display-3 fw-bold text-primary my-2">
                        #{{ $artesao->posicao_fila ?? '-' }}
                    </div>
                    <p class="text-muted small mb-0">
                        Sua posição na fila garante a rotatividade justa e prioridade na aprovação dos eventos promovidos pela Prefeitura.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- SEÇÃO 1: MINHAS CANDIDATURAS -->
    <div class="card shadow-sm border-0 mb-5">
        <div class="card-header bg-dark text-white fw-bold">
            Minhas Inscrições em Feiras / Eventos
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Evento</th>
                            <th>Data de Início</th>
                            <th>Localização</th>
                            <th class="text-center">Status da Candidatura</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($artesao->eventos as $evento)
                            <tr>
                                <td class="ps-3 fw-bold">{{ $evento->Nome }}</td>
                                <td>{{ $evento->Dia ? date('d/m/Y H:i', strtotime($evento->Dia)) : 'A definir' }}</td>
                                <td>{{ $evento->Bairro ? $evento->Rua . ', ' . $evento->Numero . ' - ' . $evento->Bairro : ($evento->Localizacao ?? 'A definir') }}</td>
                                <td class="text-center">
                                    @php $status = $evento->pivot->StatusDaCandidatura; @endphp
                                    @if($status === 'Aprovado')
                                        <span class="badge bg-success px-3 py-2">Aprovado</span>
                                    @elseif($status === 'Recusado')
                                        <span class="badge bg-danger px-3 py-2">Recusado</span>
                                    @else
                                        <span class="badge bg-warning text-dark px-3 py-2">Em Análise</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    Você ainda não se inscreveu em nenhum evento. Confira as opções disponíveis abaixo!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- SEÇÃO 2: EVENTOS DISPONÍVEIS -->
    <h3 class="fw-bold mb-3">Feiras e Eventos Abertos</h3>
    
    <div class="row">
        @php
            // IDs dos eventos em que o artesão já se candidatou
            $idsCandidatados = $artesao->eventos->pluck('ID_Evento')->toArray();
        @endphp

        @forelse($eventosDisponiveis as $evento)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-primary">{{ $evento->Nome }}</h5>
                        
                        <p class="card-text text-muted small mb-2">
                            <strong>Início:</strong> {{ $evento->Dia ? date('d/m/Y H:i', strtotime($evento->Dia)) : 'A definir' }}<br>
                            <strong>Término:</strong> {{ $evento->DataFim ? date('d/m/Y H:i', strtotime($evento->DataFim)) : 'A definir' }}
                        </p>

                        <p class="card-text text-muted small mb-3">
                            <strong>Local:</strong> {{ $evento->Bairro ? $evento->Rua . ', ' . $evento->Numero . ' - ' . $evento->Bairro : ($evento->Localizacao ?? 'A definir') }}
                        </p>

                        @if($evento->Descricao)
                            <p class="card-text small text-secondary mb-3">
                                {{ Str::limit($evento->Descricao, 100) }}
                            </p>
                        @endif

                        <div class="mt-auto pt-3 border-top">
                            @if(in_array($evento->ID_Evento, $idsCandidatados))
                                <button class="btn btn-secondary w-100 fw-bold" disabled>Já Inscrito</button>
                            @else
                                <form action="/artesao/candidatar/{{ $evento->ID_Evento }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100 fw-bold">Inscrever-se no Evento</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    Nenhum evento com inscrições abertas no momento.
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection