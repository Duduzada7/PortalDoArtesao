@extends('layouts.admin')

@section('title', 'Painel do Administrador')

@section('content')
    @php
        $pendentes = \App\Models\Artesao::where('StatusAprovacao', 'pendente')->count();

        $cards = [
            [
                'icon'  => '🎪',
                'title' => 'Novo Evento',
                'text'  => 'Cadastre uma nova feira ou exposição.',
                'href'  => '/admin/eventos/criar',
                'cta'   => 'Criar novo evento',
            ],
            [
                'icon'  => '📅',
                'title' => 'Lista de Eventos',
                'text'  => 'Veja e gerencie todos os eventos cadastrados.',
                'href'  => '/admin/eventos',
                'cta'   => 'Ver eventos',
            ],
            [
                'icon'  => '📋',
                'title' => 'Fila de Prioridade',
                'text'  => 'Acompanhe a ordem e rotatividade dos artesãos credenciados.',
                'href'  => '/admin/fila',
                'cta'   => 'Ver fila',
            ],
            [
                'icon'  => '🏷️',
                'title' => 'Especialidades',
                'text'  => 'Cadastre e gerencie as áreas de atuação dos artesãos do sistema.',
                'href'  => '/admin/especialidades',
                'cta'   => 'Gerenciar especialidades',
            ],
            [
                'icon'  => '👥',
                'title' => 'Aprovações',
                'text'  => 'Avalie os cadastros de artesãos pendentes e acompanhe os aprovados.',
                'href'  => '/admin/artesaos',
                'cta'   => 'Ver pendentes',
                'badge' => $pendentes,
            ],
            [
                'icon'  => '🛡️',
                'title' => 'Administradores',
                'text'  => 'Cadastre outros gestores da prefeitura.',
                'href'  => '/admin/gerenciar-adms',
                'cta'   => 'Gerenciar ADMs',
            ],
        ];
    @endphp

    <div class="page page--wide">
        <p class="crumbs">Administração <span aria-hidden="true">→</span> Painel</p>

        @if (session('msg'))
            <div class="flash" role="status">{{ session('msg') }}</div>
        @endif

        <header class="dash-head">
            <h1>Painel do Administrador</h1>
            @if (session('user_nome'))
                <p>Bem-vindo, <strong>{{ session('user_nome') }}</strong>!</p>
            @endif
        </header>

        <div class="dash-grid">
            @foreach ($cards as $card)
                <article class="dash-card">
                    <div class="dash-card__top">
                        <span class="dash-card__icon" aria-hidden="true">{{ $card['icon'] }}</span>
                        @if (!empty($card['badge']))
                            <span class="dash-card__badge">{{ $card['badge'] }} {{ $card['badge'] === 1 ? 'pendente' : 'pendentes' }}</span>
                        @endif
                    </div>

                    <h2>{{ $card['title'] }}</h2>
                    <p>{{ $card['text'] }}</p>

                    <a href="{{ $card['href'] }}" class="dash-card__btn">{{ $card['cta'] }}</a>
                </article>
            @endforeach
        </div>
    </div>
@endsection