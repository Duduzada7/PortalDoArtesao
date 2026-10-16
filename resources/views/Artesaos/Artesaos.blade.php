@extends('layouts.admin')

@section('title', 'Artesãos')

@section('content')
    @php
        $paleta = ['#c2531b', '#d6336c', '#7c3aed', '#059669', '#0891b2', '#7a2e12'];

        // Mantém a busca ao trocar de aba
        $urlAba = fn ($status) => request()->fullUrlWithQuery(['status' => $status === 'todos' ? null : $status]);
    @endphp

    <div class="page">
        <p class="crumbs">Administração <span aria-hidden="true">→</span> Artesãos</p>

        @if (session('msg'))
            <div class="flash" role="status">{{ session('msg') }}</div>
        @endif

        {{-- ========== PENDENTES DE APROVAÇÃO ========== --}}
        @if ($pendentes->isNotEmpty())
            <section class="pending" aria-labelledby="pending-title">
                <h2 id="pending-title" class="pending__title">
                    <span aria-hidden="true">⏳</span>
                    {{ $pendentes->count() }} {{ $pendentes->count() === 1 ? 'cadastro pendente' : 'cadastros pendentes' }} de aprovação
                </h2>

                @foreach ($pendentes as $a)
                    @php
                        $partes   = preg_split('/\s+/', trim($a->Nome));
                        $iniciais = mb_strtoupper(mb_substr($partes[0], 0, 1) . (count($partes) > 1 ? mb_substr(end($partes), 0, 1) : ''));
                    @endphp

                    <article class="person person--pending">
                        <div class="avatar" style="background: {{ $paleta[$a->ID_Artesao % count($paleta)] }}">{{ $iniciais }}</div>

                        <div class="person__main">
                            <div class="person__head">
                                <h3>{{ $a->Nome }}</h3>
                                <span class="person__badges">
                                    @if ($a->Nivel)
                                        <span class="level level--{{ Str::slug($a->Nivel) }}">{{ $a->Nivel }}</span>
                                    @endif
                                    <span class="status status--pendente">Pendente</span>
                                </span>
                            </div>

                            <p class="person__mail">{{ $a->Email }}</p>

                            @if ($a->especialidades->isNotEmpty())
                                <ul class="tags">
                                    @foreach ($a->especialidades as $esp)
                                        <li class="tag" style="--c: {{ $paleta[$esp->ID_Especialidade % count($paleta)] }}">{{ $esp->Nome }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            <p class="person__meta">
                                <span><span aria-hidden="true">📍</span> {{ $a->Bairro ? $a->Bairro . ', ' : '' }}Congonhas, MG</span>
                                <span><span aria-hidden="true">📞</span> {{ $a->Telefone }}</span>
                            </p>

                            <div class="actions">
                                <form action="/admin/aprovacoes/artesao/{{ $a->ID_Artesao }}/aprovar" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn--ok">✅ Aprovar cadastro</button>
                                </form>
                                <form action="/admin/aprovacoes/artesao/{{ $a->ID_Artesao }}/recusar" method="POST"
                                      onsubmit="return confirm('Recusar o cadastro de {{ addslashes($a->Nome) }}?')">
                                    @csrf
                                    <button type="submit" class="btn btn--no">✕ Recusar</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </section>
        @endif

        {{-- ========== BUSCA E FILTROS ========== --}}
        <div class="toolbar">
            <search class="toolbar__search">
                <form action="/admin/artesaos" method="get">
                    @if (request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    <label for="busca" class="sr-only">Buscar artesão</label>
                    <span aria-hidden="true">🔍</span>
                    <input type="search" id="busca" name="q" value="{{ request('q') }}" placeholder="Buscar por nome, e-mail, bairro...">
                </form>
            </search>

            <nav class="pills" aria-label="Filtrar por situação">
                <a href="{{ $urlAba('todos') }}"     class="pill {{ $filtro === 'todos' ? 'is-active' : '' }}">Todos ({{ $contagens['todos'] }})</a>
                <a href="{{ $urlAba('ativos') }}"    class="pill {{ $filtro === 'ativos' ? 'is-active' : '' }}">Ativos ({{ $contagens['ativos'] }})</a>
                <a href="{{ $urlAba('pendentes') }}" class="pill {{ $filtro === 'pendentes' ? 'is-active' : '' }}">Pendentes ({{ $contagens['pendentes'] }})</a>
            </nav>
        </div>

        {{-- ========== LISTA ========== --}}
        <div class="list">
            @forelse ($artesaos as $a)
                @php
                    $partes   = preg_split('/\s+/', trim($a->Nome));
                    $iniciais = mb_strtoupper(mb_substr($partes[0], 0, 1) . (count($partes) > 1 ? mb_substr(end($partes), 0, 1) : ''));
                    $ativo    = $a->StatusAprovacao === 'aprovado';
                @endphp

                <article class="person">
                    <div class="avatar" style="background: {{ $paleta[$a->ID_Artesao % count($paleta)] }}">{{ $iniciais }}</div>

                    <div class="person__main">
                        <div class="person__head">
                            <h3>{{ $a->Nome }}</h3>
                            <span class="person__badges">
                                @if ($a->Nivel)
                                    <span class="level level--{{ Str::slug($a->Nivel) }}">{{ $a->Nivel }}</span>
                                @endif
                                <span class="status {{ $ativo ? 'status--ativo' : 'status--pendente' }}">{{ $ativo ? 'Ativo' : 'Pendente' }}</span>
                            </span>
                        </div>

                        <p class="person__mail">{{ $a->Email }}</p>

                        @if ($a->especialidades->isNotEmpty())
                            <ul class="tags">
                                @foreach ($a->especialidades as $esp)
                                    <li class="tag" style="--c: {{ $paleta[$esp->ID_Especialidade % count($paleta)] }}">{{ $esp->Nome }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <p class="person__meta">
                            <span><span aria-hidden="true">📍</span> {{ $a->Bairro ? $a->Bairro . ', ' : '' }}Congonhas, MG</span>
                            <span><span aria-hidden="true">📞</span> {{ $a->Telefone }}</span>
                            @if ($ativo && $a->posicao_fila)
                                <span><span aria-hidden="true">📋</span> Fila: #{{ $a->posicao_fila }}</span>
                            @endif
                        </p>
                    </div>
                </article>
            @empty
                <p class="empty">Nenhum artesão encontrado com esses filtros.</p>
            @endforelse
        </div>
    </div>
@endsection