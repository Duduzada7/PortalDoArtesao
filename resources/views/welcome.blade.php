@extends('layouts.main')
 
@section('title', 'Portal do Artesão')
 
@section('content')
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
 
    {{-- ========== HEADER ========== --}}
    <header class="topbar">
        <div class="container topbar__inner">
            <a href="/" class="brand">
                <span class="brand__icon" aria-hidden="true">🏺</span>
                <span class="brand__name">Congonharte</span>
                <span class="brand__tag">Plataforma do Artesão</span>
            </a>
            <a href="/login" class="btn btn--primary">Entrar</a>
        </div>
    </header>
 
    {{-- ========== HERO ========== --}}
    <section class="hero">
        <div class="container hero__content">
            <p class="hero__icon" aria-hidden="true">🏺</p>
            <h1>Artesãos de Congonhas</h1>
            <p class="hero__text">
                Descubra o talento e a tradição dos artesãos da região do Alto Paraopeba.<br>
                Conecte-se diretamente com os criadores.
            </p>
 
            <search>
                <form action="/" method="get" class="search">
                    <label for="artesao-busca" class="sr-only">Buscar por artesão</label>
                    <input type="search" id="artesao-busca" name="q" value="{{ request('q') }}"
                           placeholder="Buscar por nome, especialidade, cidade...">
 
                    <label for="bairro" class="sr-only">Bairro</label>
                    <select id="bairro" name="bairro" onchange="this.form.submit()">
                        <option value="">Todos os bairros</option>
                        @foreach ($bairros as $bairro)
                            <option value="{{ $bairro }}" @selected(request('bairro') === $bairro)>{{ $bairro }}</option>
                        @endforeach
                    </select>
 
                    <button type="submit" class="sr-only">Buscar</button>
                </form>
            </search>
        </div>
 
        <div class="hero__stats">
            <dl class="container stats">
                <div><dt>Artesãos ativos</dt><dd>{{ $stats['artesaos'] }}</dd></div>
                <div><dt>Especialidades</dt><dd>{{ $stats['especialidades'] }}</dd></div>
                <div><dt>Eventos abertos</dt><dd>{{ $stats['eventos'] }}</dd></div>
                <div><dt>Bairros</dt><dd>{{ $stats['bairros'] }}</dd></div>
            </dl>
        </div>
    </section>
 
    {{-- ========== CONTEÚDO ========== --}}
    <main class="container layout">
 
        <section class="layout__main">
            {{-- Filtro por especialidade --}}
            <h2 class="label">Filtrar por especialidade</h2>
            <div class="chips">
                @foreach ($especialidades as $esp)
                    <a href="{{ request()->fullUrlWithQuery(['especialidade' => (string) request('especialidade') === (string) $esp->ID_Especialidade ? null : $esp->ID_Especialidade]) }}"
                       class="chip {{ (string) request('especialidade') === (string) $esp->ID_Especialidade ? 'chip--active' : '' }}">
                        {{ $esp->Nome }}
                    </a>
                @endforeach
            </div>
 
            <h2 class="results-title">
                {{ $artesaos->count() }}
                {{ $artesaos->count() === 1 ? 'artesão encontrado' : 'artesãos encontrados' }}
            </h2>
 
            {{-- Cards de artesãos --}}
            <div class="cards">
                @forelse ($artesaos as $artesao)
                    @php
                        $partes   = preg_split('/\s+/', trim($artesao->Nome));
                        $iniciais = mb_strtoupper(mb_substr($partes[0], 0, 1) . (count($partes) > 1 ? mb_substr(end($partes), 0, 1) : ''));
                        $fone     = preg_replace('/\D/', '', $artesao->Telefone ?? '');
                        $paleta   = ['#c2531b', '#d6336c', '#7c3aed', '#059669', '#0891b2', '#7a2e12'];
                    @endphp
 
                    <article class="card">
                        <div class="card__body">
                            <header class="card__head">
                                <div class="avatar" style="background: {{ $paleta[$artesao->ID_Artesao % count($paleta)] }}">{{ $iniciais }}</div>
                                <div class="card__id">
                                    <div class="card__row">
                                        <h3 class="card__name" title="{{ $artesao->Nome }}">{{ $artesao->Nome }}</h3>
                                        @if ($artesao->Nivel)
                                            <span class="level level--{{ Str::slug($artesao->Nivel) }}">{{ $artesao->Nivel }}</span>
                                        @endif
                                    </div>
                                    <p class="card__city">
                                        <span aria-hidden="true">📍</span>
                                        {{ $artesao->Bairro ? $artesao->Bairro . ', ' : '' }}Congonhas, MG
                                    </p>
                                </div>
                            </header>
 
                            @if ($artesao->especialidades->isNotEmpty())
                                <ul class="tags">
                                    @foreach ($artesao->especialidades as $esp)
                                        <li class="tag" style="--c: {{ $paleta[$esp->ID_Especialidade % count($paleta)] }}">{{ $esp->Nome }}</li>
                                    @endforeach
                                </ul>
                            @endif
 
                            <a href="/artesaos/{{ $artesao->ID_Artesao }}" class="card__more">Ver perfil</a>
                        </div>
 
                        @if ($fone)
                            <footer class="card__foot">
                                <a href="https://wa.me/55{{ $fone }}" class="btn-wpp" target="_blank" rel="noopener">
                                    <span aria-hidden="true">💬</span> WhatsApp
                                </a>
                                <a href="tel:+55{{ $fone }}" class="btn-phone">
                                    <span aria-hidden="true">📞</span>
                                    <span>{{ $artesao->Telefone }}</span>
                                </a>
                                <a href="/artesaos/{{ $artesao->ID_Artesao }}" class="btn-msg" aria-label="Ver perfil de {{ $artesao->Nome }}">✉</a>
                            </footer>
                        @endif
                    </article>
                @empty
                    <p class="empty">Nenhum artesão encontrado. Tente outra busca ou remova os filtros.</p>
                @endforelse
            </div>
        </section>
 
        {{-- ========== SIDEBAR ========== --}}
        <aside class="layout__side">
 
            <section class="panel">
                <h2 class="panel__title"><span aria-hidden="true">📅</span> Próximos Eventos</h2>
 
                @forelse ($eventos as $evento)
                    @php
                        $ini = \Carbon\Carbon::parse($evento->Dia);
                        $fim = $evento->DataFim ? \Carbon\Carbon::parse($evento->DataFim) : null;
                        $data = ($fim && !$fim->isSameDay($ini))
                            ? $ini->format('d') . ' a ' . $fim->format('d/m/Y')
                            : $ini->format('d/m/Y');
                    @endphp
                    <a href="/eventos/{{ $evento->ID_Evento }}" class="event">
                        <span class="event__icon" aria-hidden="true">🎪</span>
                        <span>
                            <strong class="event__title">{{ $evento->Nome }}</strong>
                            <small class="event__meta">{{ $data }}@if($evento->Bairro) · {{ $evento->Bairro }}@endif</small>
                        </span>
                    </a>
                @empty
                    <p class="empty empty--small">Nenhum evento aberto no momento.</p>
                @endforelse
 
                <a href="/eventos" class="btn-outline">Ver todos os eventos →</a>
            </section>
 
            <section class="cta">
                <p class="cta__icon" aria-hidden="true">🏅</p>
                <h2>É artesão?</h2>
                <p>Cadastre-se na plataforma e acesse eventos, capacitações e muito mais.</p>
                <a href="/login" class="btn-white">Acessar minha conta</a>
            </section>
 
            <section class="panel">
                <h2 class="label">Níveis dos artesãos</h2>
                <ul class="levels">
                    @foreach (['Novato', 'Aprendiz', 'Artesão', 'Artesão Sênior', 'Mestre', 'Grande Mestre'] as $n)
                        <li><span class="level level--{{ Str::slug($n) }}">{{ $n }}</span></li>
                    @endforeach
                </ul>
            </section>
        </aside>
    </main>
 
    {{-- ========== FOOTER ========== --}}
    <footer class="footer">
        <p class="footer__brand"><span aria-hidden="true">🏺</span> Congonharte</p>
        <p class="footer__text">Prefeitura de Congonhas · Secretaria de Cultura e Turismo · {{ date('Y') }}</p>
    </footer>
@endsection