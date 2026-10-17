@extends('layouts.admin')

@section('title', 'Eventos')

@section('content')
    @php
        $paleta = ['#c2531b', '#d6336c', '#7c3aed', '#059669', '#0891b2', '#7a2e12'];

        // "15 a 17/07/2026", "05/08/2026 a 07/09/2026" ou só "15/07/2026"
        $periodo = function ($inicio, $fim) {
            if (!$inicio) return 'Sem data';
            $i = \Carbon\Carbon::parse($inicio);
            if (!$fim) return $i->format('d/m/Y');
            $f = \Carbon\Carbon::parse($fim);
            if ($i->isSameDay($f)) return $i->format('d/m/Y');
            if ($i->format('m/Y') === $f->format('m/Y')) return $i->format('d') . ' a ' . $f->format('d/m/Y');
            return $i->format('d/m/Y') . ' a ' . $f->format('d/m/Y');
        };

        $total = $eventos->count();
    @endphp

    <div class="page">
        <p class="crumbs">Administração <span aria-hidden="true">→</span> Eventos</p>

        @if (session('msg'))
            <div class="flash" role="status">{{ session('msg') }}</div>
        @endif
        @if (session('error'))
            <div class="flash flash--error" role="alert">{{ session('error') }}</div>
        @endif

        <div class="page-head">
            <h1 class="page-head__title">{{ $total }} {{ $total === 1 ? 'evento cadastrado' : 'eventos cadastrados' }}</h1>
            <a href="/admin/eventos/criar" class="btn-new">+ Novo Evento</a>
        </div>

        <div class="events">
            @forelse ($eventos->sortByDesc('Dia') as $evento)
                @php
                    $aprovados  = $evento->artesaos->filter(fn ($a) => ($a->pivot->StatusDaCandidatura ?? null) === 'Aprovado');
                    $totalVagas = (int) $evento->Vagas;
                    $livres     = max($totalVagas - $aprovados->count(), 0);
                    $encerrado  = $evento->status === 'encerrado';

                    $local = collect([
                        trim(($evento->Rua ?? '') . ($evento->Numero ? ', ' . $evento->Numero : '')),
                        $evento->Bairro,
                    ])->filter()->implode(', ');

                    // O valor pode vir como array, JSON ou JSON gravado em duplicidade (texto dentro de texto)
                    $porEsp = $evento->especialidades_vagas;
                    for ($tentativa = 0; $tentativa < 2 && is_string($porEsp); $tentativa++) {
                        $porEsp = json_decode($porEsp, true);
                    }
                    $porEsp = is_array($porEsp) ? array_filter($porEsp, 'is_array') : [];
                @endphp

                <details class="event">
                    <summary class="event__sum">
                        <div class="event__chips">
                            <span class="chip {{ $encerrado ? 'chip--closed' : 'chip--open' }}">{{ $encerrado ? 'Encerrado' : 'Aberto' }}</span>
                            <span class="chip chip--free">{{ $livres }} {{ $livres === 1 ? 'vaga livre' : 'vagas livres' }}</span>
                        </div>
                        <h2 class="event__title">{{ $evento->Nome }}</h2>
                        <p class="event__meta">
                            {{ $periodo($evento->Dia, $evento->DataFim) }}
                            @if ($local) · {{ $local }} @endif
                            · {{ $aprovados->count() }}/{{ $totalVagas }} artesãos
                        </p>
                    </summary>

                    <div class="event__body">
                        @if ($evento->Descricao)
                            <p class="event__desc">{{ $evento->Descricao }}</p>
                        @endif

                        <h3 class="event__sub">Artesãos designados ({{ $aprovados->count() }})</h3>
                        @if ($aprovados->isNotEmpty())
                            <ul class="people">
                                @foreach ($aprovados as $a)
                                    @php
                                        $partes   = preg_split('/\s+/', trim($a->Nome));
                                        $iniciais = mb_strtoupper(mb_substr($partes[0], 0, 1) . (count($partes) > 1 ? mb_substr(end($partes), 0, 1) : ''));
                                    @endphp
                                    <li class="person-chip">
                                        <span class="avatar avatar--sm" style="background: {{ $paleta[$a->ID_Artesao % count($paleta)] }}">{{ $iniciais }}</span>
                                        {{ $a->Nome }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="event__none">Nenhum artesão designado ainda.</p>
                        @endif

                        @if (!empty($porEsp))
                            <h3 class="event__sub">Vagas por especialidade</h3>
                            <ul class="tags">
                                @foreach ($porEsp as $item)
                                    <li class="tag" style="--c: {{ $paleta[((int) ($item['id'] ?? 0)) % count($paleta)] }}">
                                        {{ $item['nome'] ?? 'Especialidade' }}: {{ $item['qtd'] ?? 0 }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="event__actions">
                            <a href="/admin/eventos/{{ $evento->ID_Evento }}/editar" class="btn-line">Editar</a>

                            @unless ($encerrado)
                                <form action="/admin/eventos/{{ $evento->ID_Evento }}/encerrar" method="POST"
                                      onsubmit="return confirm('Encerrar este evento? A fila de prioridade será reordenada.')">
                                    @csrf
                                    <button type="submit" class="btn-line">Encerrar</button>
                                </form>
                            @endunless

                            <form action="/admin/eventos/{{ $evento->ID_Evento }}" method="POST"
                                  onsubmit="return confirm('Excluir o evento {{ addslashes($evento->Nome) }}? Esta ação não pode ser desfeita.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-line btn-line--danger">Excluir</button>
                            </form>
                        </div>
                    </div>
                </details>
            @empty
                <p class="empty">Nenhum evento cadastrado ainda.</p>
            @endforelse
        </div>
    </div>
@endsection