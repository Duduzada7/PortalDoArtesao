@extends('layouts.main')

@section('title', 'Fila de Prioridade - Congonharte')

@section('content')
    <style>
        .queue-card {
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #eee8df;
            overflow: hidden;
        }

        .badge-rank {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            background-color: #eee8df;
            color: #70685c;
        }

        .badge-rank-top {
            background-color: #c04918;
            color: #ffffff;
        }

        .badge-specialty {
            border-radius: 50rem;
            padding: 0.35em 0.8em;
            font-size: 0.825rem;
            font-weight: 600;
            color: #ffffff;
        }

        .badge-xp {
            border-radius: 50rem;
            padding: 0.35em 0.75em;
            font-size: 0.8rem;
            font-weight: 600;
            background-color: #eef7ed;
            color: #2e7d32;
        }

        .info-box {
            background-color: #eef6ff;
            border: 1px solid #cce3ff;
            border-radius: 12px;
            color: #1a56a3;
        }
    </style>

    <div class="container my-5" style="max-width: 960px;">
        <!-- Título da Página -->
        <div class="mb-4">
            <h2 class="fw-bold d-flex align-items-center gap-2" style="color: #212529;">
                <span>📋</span> Fila de Prioridade
            </h2>
            <p class="text-muted mb-0">
                Artesãos na posição 1 têm mais prioridade para serem selecionados. Após participar de um evento, o artesão
                vai para o final da fila[cite: 7].
            </p>
        </div>

        <!-- Tabela da Fila -->
        <div class="queue-card mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: #f7f3ed; color: #786f66;" class="text-uppercase small">
                        <tr>
                            <th class="ps-4 py-3" style="width: 70px;">#</th>
                            <th class="py-3">Artesão</th>
                            <th class="py-3">Especialidade</th>
                            <th class="py-3 text-center">XP</th>
                            <th class="pe-4 py-3 text-end" style="width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($artesaos as $index => $artesao)
                            <tr>
                                <!-- Posição na Fila -->
                                <td class="ps-4">
                                    <span class="badge-rank {{ $loop->first ? 'badge-rank-top' : '' }}">
                                        {{ $loop->iteration }}
                                    </span>
                                </td>

                                <!-- Nome e Cidade -->
                                <td>
                                    <div class="fw-bold text-dark">{{ $artesao->Nome }}</div>
                                    <small class="text-muted">{{ $artesao->Cidade ?? 'Congonhas' }}</small>
                                </td>

                                <!-- Especialidades -->
                                <td>
                                    @php
                                        $bgColors = ['#15803d', '#6b21a8', '#854d0e', '#c2410c', '#be185d', '#0369a1'];
                                        $bgColor = $bgColors[$index % count($bgColors)];
                                    @endphp
                                    <span class="badge-specialty" style="background-color: {{ $bgColor }};">
                                        {{ $artesao->especialidade->Nome ?? $artesao->Especialidade ?? 'Artesanato' }}
                                    </span>
                                    @if(isset($artesao->outras_especialidades_count) && $artesao->outras_especialidades_count > 0)
                                        <span class="text-muted small ms-1">+{{ $artesao->outras_especialidades_count }}</span>
                                    @endif
                                </td>

                                <!-- Pontuação XP -->
                                <td class="text-center">
                                    <span class="badge-xp">
                                        {{ $artesao->xp ?? 0 }} XP
                                    </span>
                                </td>

                                <!-- Ícone de Ordenação/Ação -->
                                <td class="pe-4 text-end text-muted">
                                    <small>&darr;</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    Nenhum artesão cadastrado na fila de prioridade no momento.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Informação Explicativa -->
        <div class="info-box p-3 d-flex align-items-start gap-2">
            <span class="fs-5">ℹ️</span>
            <div>
                <strong>Como funciona:</strong> Quando um artesão é aprovado para participar de um evento, ele é
                automaticamente movido para o final da fila. Isso garante equidade e rotatividade na participação dos
                artesãos[cite: 7].
            </div>
        </div>
    </div>
@endsection