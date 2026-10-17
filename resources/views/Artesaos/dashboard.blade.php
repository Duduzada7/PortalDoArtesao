<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Artesão · Congonharte</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS Externo -->
    <link rel="stylesheet" href="{{ asset('css/artesao-dashboard.css') }}">
</head>
<body>

@php
    $partesNome = preg_split('/\s+/', trim($artesao->Nome));
    $iniciais   = mb_strtoupper(mb_substr($partesNome[0], 0, 1) . (count($partesNome) > 1 ? mb_substr(end($partesNome), 0, 1) : ''));
    $aprovadosCount = $artesao->eventos->filter(fn($e) => $e->pivot->StatusDaCandidatura === 'Aprovado')->count();
@endphp

<div class="artesao-shell">
    
    <!-- SIDEBAR ESQUERDA -->
    <aside class="artesao-sidebar">
        <div class="artesao-sidebar__top">
            <a href="/" class="artesao-sidebar__brand">
                <span aria-hidden="true">🏺</span> Congonharte
            </a>

            <div class="artesao-user-card">
                <div class="artesao-avatar">{{ $iniciais }}</div>
                <div class="artesao-user-info">
                    <h4>{{ $artesao->Nome }}</h4>
                    <p>Artesão Credenciado</p>
                </div>
            </div>

            <nav class="artesao-nav">
                <a href="#" class="artesao-nav__link active">
                    <span>📊</span> Painel
                </a>
                <a href="#eventos-abertos" class="artesao-nav__link">
                    <span>🎪</span> Eventos
                </a>
                <a href="#minhas-inscricoes" class="artesao-nav__link">
                    <span>📝</span> Inscrições
                </a>
            </nav>
        </div>

        <!-- BOTÃO DE SAIR NO RODAPÉ DA SIDEBAR -->
        <div class="artesao-sidebar__bottom">
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="btn-logout-sidebar">
                    <span aria-hidden="true">🚪</span> Sair
                </button>
            </form>
        </div>
    </aside>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="artesao-main">
        
        <header class="artesao-header">
            <h1>Olá, {{ $artesao->Nome }}! 👋</h1>
            <p>Aqui está o resumo do seu perfil e das suas candidaturas em feiras e eventos.</p>
        </header>

        @if(session('msg'))
            <div style="background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; padding: 12px 16px; border-radius: 12px; margin-bottom: 24px; font-size: 13px; font-weight: 600;">
                {{ session('msg') }}
            </div>
        @endif

        <!-- CARDS DE MÉTRICAS -->
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-card__icon icon-orange">📍</div>
                <div>
                    <div class="metric-card__value">#{{ $artesao->posicao_fila ?? '-' }}</div>
                    <div class="metric-card__label">Posição na Fila</div>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-card__icon icon-green">✅</div>
                <div>
                    <div class="metric-card__value">{{ $aprovadosCount }}</div>
                    <div class="metric-card__label">Eventos Aprovados</div>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-card__icon icon-purple">🏷️</div>
                <div>
                    <div class="metric-card__value">{{ $artesao->especialidades->count() }}</div>
                    <div class="metric-card__label">Especialidades</div>
                </div>
            </div>
        </div>

        <!-- PAINEL DE INFORMAÇÕES PESSOAIS -->
        <div class="artesao-card-panel">
            <div class="artesao-card-panel__title">
                <span>👤 Minhas Informações</span>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; font-size: 13px;">
                <div>
                    <strong style="color: var(--muted); font-size: 11px; text-transform: uppercase;">E-mail</strong>
                    <div style="font-weight: 600; margin-top: 2px;">{{ $artesao->Email }}</div>
                </div>
                <div>
                    <strong style="color: var(--muted); font-size: 11px; text-transform: uppercase;">Telefone</strong>
                    <div style="font-weight: 600; margin-top: 2px;">{{ $artesao->Telefone }}</div>
                </div>
                <div>
                    <strong style="color: var(--muted); font-size: 11px; text-transform: uppercase;">Endereço</strong>
                    <div style="font-weight: 600; margin-top: 2px;">
                        {{ $artesao->Rua ? $artesao->Rua . ', ' . $artesao->Numero . ' - ' . $artesao->Bairro : 'Não informado' }}
                    </div>
                </div>
            </div>

            @if($artesao->especialidades->isNotEmpty())
                <div style="margin-top: 18px; padding-top: 14px; border-top: 1px solid var(--line);">
                    <strong style="color: var(--muted); font-size: 11px; text-transform: uppercase; display: block; margin-bottom: 8px;">Especialidades Vinculadas</strong>
                    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                        @foreach($artesao->especialidades as $esp)
                            <span style="background: #fbeee6; color: var(--rust); font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 999px;">
                                {{ $esp->Nome }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- TABELA DE MINHAS INSCRIÇÕES -->
        <div class="artesao-card-panel" id="minhas-inscricoes">
            <div class="artesao-card-panel__title">
                <span>📝 Minhas Inscrições em Eventos</span>
            </div>
            
            <div style="overflow-x: auto;">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Evento</th>
                            <th>Data de Início</th>
                            <th>Localização</th>
                            <th style="text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($artesao->eventos as $evento)
                            @php $status = $evento->pivot->StatusDaCandidatura; @endphp
                            <tr>
                                <td style="font-weight: 700;">{{ $evento->Nome }}</td>
                                <td>{{ $evento->Dia ? date('d/m/Y H:i', strtotime($evento->Dia)) : 'A definir' }}</td>
                                <td>{{ $evento->Bairro ? $evento->Rua . ', ' . $evento->Numero . ' - ' . $evento->Bairro : ($evento->Localizacao ?? 'A definir') }}</td>
                                <td style="text-align: center;">
                                    @if($status === 'Aprovado')
                                        <span class="status-pill status-pill--aprovado">Aprovado</span>
                                    @elseif($status === 'Recusado')
                                        <span class="status-pill status-pill--recusado">Recusado</span>
                                    @else
                                        <span class="status-pill status-pill--pendente">Em Análise</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--muted); padding: 24px;">
                                    Você ainda não se inscreveu em nenhum evento.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- GRID DE EVENTOS DISPONÍVEIS -->
        <div class="artesao-card-panel" id="eventos-abertos">
            <div class="artesao-card-panel__title">
                <span>🎪 Feiras e Eventos Abertos</span>
            </div>

            @php
                $idsCandidatados = $artesao->eventos->pluck('ID_Evento')->toArray();
            @endphp

            <div class="events-grid">
                @forelse($eventosDisponiveis as $evento)
                    <div class="event-card">
                        <div>
                            <div class="event-card__title">{{ $evento->Nome }}</div>
                            <div class="event-card__info">
                                <strong>📅 Início:</strong> {{ $evento->Dia ? date('d/m/Y H:i', strtotime($evento->Dia)) : 'A definir' }}<br>
                                <strong>📍 Local:</strong> {{ $evento->Bairro ? $evento->Rua . ', ' . $evento->Numero . ' - ' . $evento->Bairro : ($evento->Localizacao ?? 'A definir') }}
                            </div>
                            @if($evento->Descricao)
                                <div style="font-size: 12px; color: var(--muted); margin-bottom: 16px;">
                                    {{ Str::limit($evento->Descricao, 80) }}
                                </div>
                            @endif
                        </div>

                        <div>
                            @if(in_array($evento->ID_Evento, $idsCandidatados))
                                <button class="btn-inscrever" disabled>Já Inscrito</button>
                            @else
                                <form action="/artesao/candidatar/{{ $evento->ID_Evento }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-inscrever">Inscrever-se no Evento</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; color: var(--muted); font-size: 13px; text-align: center; padding: 24px;">
                        Nenhum evento com inscrições abertas no momento.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- BANNER INFORMATIVO DA FILA -->
        <div class="queue-banner">
            <div style="font-size: 28px;">📋</div>
            <div>
                <h5 style="font-size: 14px; font-weight: 700; margin-bottom: 2px;">Fila de Prioridade para Eventos</h5>
                <p style="font-size: 12px; opacity: 0.9;">Você está na posição <strong>#{{ $artesao->posicao_fila ?? '-' }}</strong> da fila. Quanto menor o número da posição, maior é a sua prioridade de seleção para os próximos eventos!</p>
            </div>
        </div>

    </main>
</div>

</body>
</html>