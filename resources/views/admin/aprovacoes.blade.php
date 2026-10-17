@extends('layouts.admin')

@section('title', 'Inscrições')

@section('content')
    @php
        $paleta   = ['#c2531b', '#d6336c', '#7c3aed', '#059669', '#0891b2', '#7a2e12'];
        $evFiltro = request('evento');

        $iniciaisDe = function ($nome) {
            $p = preg_split('/\s+/', trim($nome));
            return mb_strtoupper(mb_substr($p[0], 0, 1) . (count($p) > 1 ? mb_substr(end($p), 0, 1) : ''));
        };

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

        $pendentesEventos = $eventosComCandidaturas->sum(
            fn ($e) => $e->artesaos->filter(fn ($a) => $a->pivot->StatusDaCandidatura === 'Inscrito')->count()
        );

        $eventosVisiveis = $eventosComCandidaturas->when(
            $evFiltro,
            fn ($c) => $c->where('ID_Evento', (int) $evFiltro)
        );
    @endphp

    <div class="page page--full">
        <p class="crumbs">Administração <span aria-hidden="true">→</span> Inscrições</p>

        <header class="page-title">
            <h1><span aria-hidden="true">📝</span> Gerenciar Inscrições</h1>
            <p>Aprove ou recuse cadastros de artesãos e inscrições nos eventos.</p>
        </header>

        @if (session('msg'))
            <div class="flash" role="status">{{ session('msg') }}</div>
        @endif
        @if (session('error'))
            <div class="flash flash--error" role="alert">{{ session('error') }}</div>
        @endif

        <div class="split">

            {{-- ========== COLUNA 1: CANDIDATURAS EM EVENTOS ========== --}}
            <section class="split__col" aria-labelledby="col-eventos">
                <header class="col-head">
                    <h2 id="col-eventos">Candidaturas em Eventos</h2>
                    <span class="col-head__count" title="Candidaturas pendentes">{{ $pendentesEventos }}</span>
                </header>

                <form action="/admin/aprovacoes" method="get" class="col-filter">
                    <label for="filtro-evento" class="sr-only">Filtrar por evento</label>
                    <select id="filtro-evento" name="evento" onchange="this.form.submit()">
                        <option value="">Todos os eventos</option>
                        @foreach ($eventosComCandidaturas as $ev)
                            <option value="{{ $ev->ID_Evento }}" @selected((string) $evFiltro === (string) $ev->ID_Evento)>{{ $ev->Nome }}</option>
                        @endforeach
                    </select>
                </form>

                @forelse ($eventosVisiveis as $evento)
                    @php
                        $pendentesDoEvento = $evento->artesaos->filter(fn ($a) => $a->pivot->StatusDaCandidatura === 'Inscrito')->count();
                    @endphp

                    <article class="insc-card">
                        <header class="insc-card__head">
                            <div class="event__chips">
                                @if ($evento->status === 'encerrado')
                                    <span class="chip chip--closed">Encerrado</span>
                                @endif
                                @if ($pendentesDoEvento > 0)
                                    <span class="chip chip--open">{{ $pendentesDoEvento }} {{ $pendentesDoEvento === 1 ? 'pendente' : 'pendentes' }}</span>
                                @endif
                            </div>
                            <h3 class="insc-card__title">{{ $evento->Nome }}</h3>
                            <p class="insc-card__meta">
                                {{ $periodo($evento->Dia, $evento->DataFim) }}
                                · {{ $evento->artesaos->count() }} {{ $evento->artesaos->count() === 1 ? 'inscrição' : 'inscrições' }}
                            </p>
                        </header>

                        @foreach ($evento->artesaos as $artesao)
                            @php
                                $situacao = $artesao->pivot->StatusDaCandidatura;
                                $inscritoEm = $artesao->pivot->created_at
                                    ? \Carbon\Carbon::parse($artesao->pivot->created_at)->format('d/m/Y')
                                    : null;
                            @endphp

                            <div class="insc-row">
                                <span class="avatar avatar--md" style="background: {{ $paleta[$artesao->ID_Artesao % count($paleta)] }}">{{ $iniciaisDe($artesao->Nome) }}</span>

                                <div class="insc-row__main">
                                    <p class="insc-row__name">{{ $artesao->Nome }}</p>
                                    <p class="insc-row__sub">
                                        {{ $artesao->posicao_fila ? 'Fila #' . $artesao->posicao_fila : 'Sem posição na fila' }}
                                        @if ($inscritoEm) · Inscrito em {{ $inscritoEm }} @endif
                                        @if ($artesao->especialidades->isNotEmpty()) · {{ $artesao->especialidades->pluck('Nome')->implode(', ') }} @endif
                                    </p>
                                </div>

                                <div class="insc-row__end">
                                    @if ($situacao === 'Aprovado')
                                        <span class="status status--ativo">Aprovado</span>
                                    @elseif ($situacao === 'Recusado')
                                        <span class="status status--recusado">Recusado</span>
                                    @else
                                        <span class="status status--pendente">Pendente</span>

                                        <form action="/admin/aprovacoes/evento/{{ $evento->ID_Evento }}/artesao/{{ $artesao->ID_Artesao }}/aprovar" method="POST">
                                            @csrf
                                            <button type="submit" class="icon-btn icon-btn--ok" title="Aprovar vaga" aria-label="Aprovar vaga de {{ $artesao->Nome }}">✓</button>
                                        </form>
                                        <form action="/admin/aprovacoes/evento/{{ $evento->ID_Evento }}/artesao/{{ $artesao->ID_Artesao }}/recusar" method="POST"
                                              onsubmit="return confirm('Recusar a vaga de {{ addslashes($artesao->Nome) }}?')">
                                            @csrf
                                            <button type="submit" class="icon-btn icon-btn--no" title="Recusar vaga" aria-label="Recusar vaga de {{ $artesao->Nome }}">✕</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </article>
                @empty
                    <p class="empty">Nenhuma inscrição em eventos no momento.</p>
                @endforelse
            </section>

            {{-- ========== COLUNA 2: CANDIDATURAS DE ARTESÃOS (CADASTROS) ========== --}}
            <section class="split__col" aria-labelledby="col-artesaos">
                <header class="col-head">
                    <h2 id="col-artesaos">Candidaturas de Artesãos</h2>
                    <span class="col-head__count" title="Cadastros pendentes">{{ $artesaosPendentes->count() }}</span>
                </header>

                <article class="insc-card">
                    @forelse ($artesaosPendentes as $artesao)
                        <div class="insc-row">
                            <span class="avatar avatar--md" style="background: {{ $paleta[$artesao->ID_Artesao % count($paleta)] }}">{{ $iniciaisDe($artesao->Nome) }}</span>

                            <div class="insc-row__main">
                                <p class="insc-row__name">{{ $artesao->Nome }}</p>
                                <p class="insc-row__sub">
                                    {{ $artesao->Email }} · {{ $artesao->Telefone }}
                                    @if ($artesao->Bairro) · {{ $artesao->Bairro }} @endif
                                </p>
                                @if ($artesao->especialidades->isNotEmpty())
                                    <ul class="tags">
                                        @foreach ($artesao->especialidades as $esp)
                                            <li class="tag" style="--c: {{ $paleta[$esp->ID_Especialidade % count($paleta)] }}">{{ $esp->Nome }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>

                            <div class="insc-row__end">
                                <span class="status status--pendente">Pendente</span>

                                <form action="/admin/aprovacoes/artesao/{{ $artesao->ID_Artesao }}/aprovar" method="POST">
                                    @csrf
                                    <button type="submit" class="icon-btn icon-btn--ok" title="Aprovar cadastro" aria-label="Aprovar cadastro de {{ $artesao->Nome }}">✓</button>
                                </form>
                                <form action="/admin/aprovacoes/artesao/{{ $artesao->ID_Artesao }}/recusar" method="POST"
                                      onsubmit="return confirm('Recusar o cadastro de {{ addslashes($artesao->Nome) }}?')">
                                    @csrf
                                    <button type="submit" class="icon-btn icon-btn--no" title="Recusar cadastro" aria-label="Recusar cadastro de {{ $artesao->Nome }}">✕</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="insc-empty">Nenhum cadastro de artesão pendente.</p>
                    @endforelse
                </article>
            </section>

        </div>
    </div>
@endsection