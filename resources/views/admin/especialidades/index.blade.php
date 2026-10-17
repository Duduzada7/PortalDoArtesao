@extends('layouts.admin')

@section('title', 'Especialidades')

@section('content')
    @php
        $paleta = ['#c2531b', '#d6336c', '#7c3aed', '#059669', '#0891b2', '#7a2e12'];
    @endphp

    <div class="page">
        <p class="crumbs">Administração <span aria-hidden="true">→</span> Especialidades</p>

        <header class="page-title">
            <h1><span aria-hidden="true">🏷️</span> Gerenciar Especialidades</h1>
            <p>Cadastre e gerencie as áreas de atuação dos artesãos do sistema.</p>
        </header>

        @if (session('msg'))
            <div class="flash" role="status">{{ session('msg') }}</div>
        @endif
        @if (session('error'))
            <div class="flash flash--error" role="alert">{{ session('error') }}</div>
        @endif

        {{-- ========== NOVA ESPECIALIDADE ========== --}}
        <section class="form-card form-card--compact" aria-labelledby="nova-esp">
            <h2 id="nova-esp" class="form-card__title">Nova Especialidade</h2>

            <form action="/admin/especialidades" method="POST" class="inline-form">
                @csrf
                <div class="field">
                    <label for="Nome" class="sr-only">Nome da especialidade</label>
                    <input type="text" id="Nome" name="Nome" value="{{ old('Nome') }}"
                           class="@error('Nome') is-invalid @enderror"
                           placeholder="Ex: Marcenaria, Saboaria, Macramê..." required>
                    @error('Nome')
                        <p class="field__error">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="btn-submit">+ Adicionar</button>
            </form>
        </section>

        {{-- ========== ESPECIALIDADES CADASTRADAS ========== --}}
        <header class="col-head">
            <h2>Especialidades Cadastradas</h2>
            <span class="col-head__count">{{ $especialidades->count() }}</span>
        </header>

        <div class="insc-card">
            @forelse ($especialidades as $esp)
                <div class="insc-row">
                    <span class="avatar avatar--sq" style="background: {{ $paleta[$esp->ID_Especialidade % count($paleta)] }}">
                        {{ mb_strtoupper(mb_substr($esp->Nome, 0, 1)) }}
                    </span>

                    <div class="insc-row__main">
                        <p class="insc-row__name">{{ $esp->Nome }}</p>
                        <p class="insc-row__sub">Especialidade #{{ $esp->ID_Especialidade }}</p>
                    </div>

                    <div class="insc-row__end">
                        <form action="/admin/especialidades/{{ $esp->ID_Especialidade }}" method="POST"
                              onsubmit="return confirm('Tem certeza que deseja remover a especialidade {{ addslashes($esp->Nome) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-line btn-line--danger">Excluir</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="insc-empty">Nenhuma especialidade cadastrada até o momento.</p>
            @endforelse
        </div>
    </div>
@endsection