<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administração') · Congonharte</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    @php
        // Dados da sidebar (admin logado e contadores)
        $adminLogado     = \App\Models\Adm::find(session('user_id'));
        $contPendentes   = \App\Models\Artesao::where('StatusAprovacao', 'pendente')->count();
        $contInscricoes  = \Illuminate\Support\Facades\DB::table('candidatura')
                                ->where('StatusDaCandidatura', 'Inscrito')->count();

        $menu = [
            ['label' => 'Artesãos',            'icon' => '👥', 'href' => '/admin/artesaos',   'match' => 'admin/artesaos*',   'count' => $contPendentes],
            ['label' => 'Eventos',             'icon' => '🎪', 'href' => '/admin/eventos',    'match' => 'admin/eventos*',    'count' => 0],
            ['label' => 'Fila de Prioridade',  'icon' => '📋', 'href' => '/admin/fila',       'match' => 'admin/fila*',       'count' => 0],
            ['label' => 'Inscrições',          'icon' => '📝', 'href' => '/admin/aprovacoes?tab=eventos', 'match' => 'admin/aprovacoes*', 'count' => $contInscricoes],
        ];
    @endphp

    <div class="shell">
        <aside class="side">
            <div class="side__top">
                <a href="/admin/dashboard" class="side__brand">
                    <span aria-hidden="true">🏺</span> Congonharte
                </a>

                <div class="side__user">
                    <small>Logado como</small>
                    <strong>Administrador</strong>
                    <span>{{ $adminLogado->Email ?? '' }}</span>
                </div>
            </div>

            <nav class="side__nav" aria-label="Menu do administrador">
                @foreach ($menu as $item)
                    <a href="{{ $item['href'] }}" class="side__link {{ request()->is($item['match']) ? 'is-active' : '' }}">
                        <span class="side__icon" aria-hidden="true">{{ $item['icon'] }}</span>
                        <span>{{ $item['label'] }}</span>
                        @if ($item['count'] > 0)
                            <span class="side__count">{{ $item['count'] }}</span>
                        @endif
                    </a>
                @endforeach
            </nav>

            <form action="/logout" method="POST" class="side__bottom">
                @csrf
                <button type="submit" class="side__logout"><span aria-hidden="true">🚪</span> Sair</button>
            </form>
        </aside>

        <main class="main">
            @yield('content')
        </main>
    </div>
</body>
</html>