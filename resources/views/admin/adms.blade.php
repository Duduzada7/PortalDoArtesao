@extends('layouts.admin')

@section('title', 'Administradores')

@section('content')
    @php
        $paleta = ['#c2531b', '#d6336c', '#7c3aed', '#059669', '#0891b2', '#7a2e12'];

        $iniciaisDe = function ($nome) {
            $p = preg_split('/\s+/', trim($nome));
            return mb_strtoupper(mb_substr($p[0], 0, 1) . (count($p) > 1 ? mb_substr(end($p), 0, 1) : ''));
        };
    @endphp

    <div class="page page--full">
        <p class="crumbs">Administração <span aria-hidden="true">→</span> Administradores</p>

        <header class="page-title">
            <h1><span aria-hidden="true">🛡️</span> Gerenciar Administradores</h1>
            <p>Cadastre outros gestores da prefeitura e controle quem tem acesso ao painel.</p>
        </header>

        @if (session('msg'))
            <div class="flash" role="status">{{ session('msg') }}</div>
        @endif
        @if (session('error'))
            <div class="flash flash--error" role="alert">{{ session('error') }}</div>
        @endif

        <div class="split">

            {{-- ========== COLUNA 1: CADASTRO ========== --}}
            <section class="split__col" aria-labelledby="col-cadastro">
                <div class="form-card">
                    <h2 id="col-cadastro" class="form-card__title">Cadastrar Novo Administrador</h2>

                    <form action="/admin/gerenciar-adms" method="POST">
                        @csrf

                        <div class="field">
                            <label for="Nome">Nome completo <span class="req">*</span></label>
                            <input type="text" id="Nome" name="Nome" value="{{ old('Nome') }}"
                                   class="@error('Nome') is-invalid @enderror"
                                   placeholder="Ex: Maria da Silva" required>
                            @error('Nome')
                                <p class="field__error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="Email">E-mail <span class="req">*</span></label>
                            <input type="email" id="Email" name="Email" value="{{ old('Email') }}"
                                   class="@error('Email') is-invalid @enderror"
                                   placeholder="admin@prefeitura.gov.br" required>
                            @error('Email')
                                <p class="field__error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="Senha">Senha de acesso <span class="req">*</span></label>
                            <input type="password" id="Senha" name="Senha" autocomplete="new-password"
                                   class="@error('Senha') is-invalid @enderror"
                                   placeholder="Mínimo 6 caracteres" required>
                            @error('Senha')
                                <p class="field__error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-submit">Cadastrar ADM</button>
                            <a href="/admin/dashboard" class="btn-cancel">Voltar ao Painel</a>
                        </div>
                    </form>
                </div>
            </section>

            {{-- ========== COLUNA 2: ADMINISTRADORES CADASTRADOS ========== --}}
            <section class="split__col" aria-labelledby="col-lista">
                <header class="col-head">
                    <h2 id="col-lista">Administradores Cadastrados</h2>
                    <span class="col-head__count">{{ $adms->count() }}</span>
                </header>

                <div class="insc-card">
                    @forelse ($adms as $adm)
                        @php
                            $admId  = $adm->Id_ADM ?? $adm->id;
                            $ehVoce = (string) $admId === (string) session('user_id');
                        @endphp

                        <div class="insc-row">
                            <span class="avatar avatar--md" style="background: {{ $paleta[((int) $admId) % count($paleta)] }}">{{ $iniciaisDe($adm->Nome) }}</span>

                            <div class="insc-row__main">
                                <p class="insc-row__name">{{ $adm->Nome }}</p>
                                <p class="insc-row__sub">{{ $adm->Email }}</p>
                            </div>

                            <div class="insc-row__end">
                                @if ($ehVoce)
                                    <span class="badge-you">Você</span>
                                @else
                                    <form action="/admin/gerenciar-adms/{{ $admId }}" method="POST"
                                          onsubmit="return confirm('Tem certeza que deseja remover {{ addslashes($adm->Nome) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-line btn-line--danger">Remover</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="insc-empty">Nenhum administrador cadastrado.</p>
                    @endforelse
                </div>
            </section>

        </div>
    </div>
@endsection