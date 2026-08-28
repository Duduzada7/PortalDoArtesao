@extends('layouts.main')

@section('title', 'Aprovações Pendentes - Portal do Artesão')

@section('content')
@php
    $tabAtiva = request('tab', 'artesaos'); // Se não vier parâmetro, a padrão é 'artesaos'
@endphp

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Solicitações Pendentes</h2>
        <a href="/admin/dashboard" class="btn btn-outline-secondary">Voltar ao Painel</a>
    </div>

    @if(session('msg'))
        <div class="alert alert-success">{{ session('msg') }}</div>
    @endif

    {{-- Navegação por Abas --}}
    <ul class="nav nav-tabs" id="aprovacaoTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold {{ $tabAtiva === 'artesaos' ? 'active' : '' }}" 
                    id="artesaos-tab" 
                    data-bs-toggle="tab" 
                    data-bs-target="#artesaos-pane" 
                    type="button" 
                    role="tab">
                Cadastros de Artesãos ({{ $artesaosPendentes->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold {{ $tabAtiva === 'eventos' ? 'active' : '' }}" 
                    id="eventos-tab" 
                    data-bs-toggle="tab" 
                    data-bs-target="#eventos-pane" 
                    type="button" 
                    role="tab">
                Candidaturas em Eventos ({{ $eventosComCandidaturas->sum(fn($e) => $e->artesaos->count()) }})
            </button>
        </li>
    </ul>

    <div class="tab-content border border-top-0 p-4 bg-white rounded-bottom shadow-sm" id="aprovacaoTabsContent">
        
        {{-- ABA 1: ARTESÃOS PENDENTES NO SISTEMA --}}
        <div class="tab-pane fade {{ $tabAtiva === 'artesaos' ? 'show active' : '' }}" id="artesaos-pane" role="tabpanel">
            <h4 class="mb-3">Novos Cadastros Aguardando Aprovação</h4>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nome</th>
                            <th>Contato</th>
                            <th>Localização</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($artesaosPendentes as $artesao)
                            <tr>
                                <td class="fw-bold">{{ $artesao->Nome }}</td>
                                <td>
                                    <small class="d-block">Email: {{ $artesao->Email }}</small>
                                    <small class="d-block">Tel: {{ $artesao->Telefone }}</small>
                                </td>
                                <td>{{ $artesao->Bairro ?? 'Não informado' }}</td>
                                <td class="text-end">
                                    <form action="/admin/aprovacoes/artesao/{{ $artesao->ID_Artesao }}/aprovar" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success">Aprovar</button>
                                    </form>
                                    <form action="/admin/aprovacoes/artesao/{{ $artesao->ID_Artesao }}/recusar" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger">Recusar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Nenhum cadastro de artesão pendente.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ABA 2: CANDIDATURAS EM EVENTOS --}}
        <div class="tab-pane fade {{ $tabAtiva === 'eventos' ? 'show active' : '' }}" id="eventos-pane" role="tabpanel">
            <h4 class="mb-3">Inscrições em Eventos Aguardando Avaliação</h4>

            @forelse($eventosComCandidaturas as $evento)
                <div class="card mb-4 border-secondary">
                    <div class="card-header bg-light fw-bold">
                        Evento: {{ $evento->Nome }}
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">Artesão</th>
                                    <th>Telefone</th>
                                    <th class="text-end pe-3">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($evento->artesaos as $artesao)
                                    <tr>
                                        <td class="ps-3 fw-bold">{{ $artesao->Nome }}</td>
                                        <td>{{ $artesao->Telefone }}</td>
                                        <td class="text-end pe-3">
                                            <form action="/admin/aprovacoes/evento/{{ $evento->ID_Evento }}/artesao/{{ $artesao->ID_Artesao }}/aprovar" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-success">Aprovar Vaga</button>
                                            </form>
                                            <form action="/admin/aprovacoes/evento/{{ $evento->ID_Evento }}/artesao/{{ $artesao->ID_Artesao }}/recusar" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-danger">Recusar Vaga</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <p class="text-muted text-center py-4">Nenhuma candidatura pendente para eventos no momento.</p>
            @endforelse
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection