@extends('layouts.main')

@section('title', 'Cadastrar Artesão - Congonharte')

@section('content')
<style>
    :root {
        --rust: #c04918;
        --rust-hover: #a33c11;
        --cream-bg: #f7f4f0;
        --card-bg: #ffffff;
        --ink: #2a1a12;
        --muted: #786f66;
        --line: #eee8df;
        --radius-lg: 20px;
        --radius-md: 14px;
        --radius-sm: 10px;
    }

    body {
        background-color: var(--cream-bg) !important;
        font-family: 'Outfit', system-ui, -apple-system, sans-serif !important;
        color: var(--ink);
    }

    .register-wrapper {
        max-width: 650px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .register-card {
        background: var(--card-bg);
        border: 1px solid var(--line);
        border-radius: var(--radius-lg);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .register-header {
        background-color: var(--rust);
        color: #ffffff;
        padding: 24px 30px;
    }

    .register-header h4 {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
    }

    .register-body {
        padding: 30px;
    }

    .form-label-custom {
        display: block;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--muted);
        margin-bottom: 8px;
    }

    .form-control-custom {
        width: 100%;
        background: #faf6f0;
        border: 1px solid var(--line);
        border-radius: var(--radius-md);
        padding: 12px 16px;
        font-size: 13px;
        color: var(--ink);
        outline: none;
        transition: all 0.2s;
    }

    .form-control-custom:focus {
        border-color: var(--rust);
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(192, 73, 24, 0.1);
    }

    .form-check-custom {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #faf6f0;
        padding: 10px 14px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--line);
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
    }

    .form-check-input {
        accent-color: var(--rust);
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .btn-submit-custom {
        width: 100%;
        background-color: var(--rust);
        color: #ffffff;
        border: none;
        border-radius: var(--radius-md);
        padding: 14px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.2s;
        margin-top: 10px;
    }

    .btn-submit-custom:hover {
        background-color: var(--rust-hover);
    }

    .alert-danger-custom {
        background: #fee2e2;
        border: 1px solid #fecaca;
        color: #b91c1c;
        padding: 14px;
        border-radius: var(--radius-md);
        font-size: 13px;
        margin-bottom: 20px;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 16px;
        font-size: 13px;
        font-weight: 600;
        color: var(--rust);
        text-decoration: none;
    }

    .back-link:hover {
        text-decoration: underline;
    }
</style>

<div class="register-wrapper">
    <a href="/login" class="back-link">&larr; Voltar ao login</a>

    <div class="register-card">
        <div class="register-header">
            <h4>Cadastro de Artesão</h4>
        </div>
        <div class="register-body">
            @if ($errors->any())
                <div class="alert-danger-custom">
                    <ul class="mb-0" style="padding-left: 16px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/artesao/cadastrar" method="POST">
                @csrf

                <div style="margin-bottom: 18px;">
    <label for="Nome" class="form-label-custom">Nome Completo: <span style="color: #dc2626;">*</span></label>
    <input type="text" class="form-control-custom" id="Nome" name="Nome" value="{{ old('Nome') }}" required placeholder="Ex: Maria Silva">
</div>  

                <div style="margin-bottom: 18px;">
                    <label for="Email" class="form-label-custom">E-mail: <span style="color: #dc2626;">*</span></label>
                    <input type="email" class="form-control-custom" id="Email" name="Email" value="{{ old('Email') }}" required placeholder="exemplo@email.com">
                </div>

                <div style="margin-bottom: 18px;">
                    <label for="Telefone" class="form-label-custom">Telefone: <span style="color: #dc2626;">*</span></label>
                    <input type="text" class="form-control-custom" id="Telefone" name="Telefone" value="{{ old('Telefone') }}" required placeholder="(31) 99999-9999">
                </div>

                {{-- Endereço Detalhado --}}
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 18px;">
                    <div>
                        <label for="Rua" class="form-label-custom">Rua / Logradouro:</label>
                        <input type="text" class="form-control-custom" id="Rua" name="Rua" value="{{ old('Rua') }}" placeholder="Ex: Rua das Flores">
                    </div>
                    <div>
                        <label for="Numero" class="form-label-custom">Número:</label>
                        <input type="text" class="form-control-custom" id="Numero" name="Numero" value="{{ old('Numero') }}" placeholder="123">
                    </div>
                </div>

                <div style="margin-bottom: 18px;">
                    <label for="Bairro" class="form-label-custom">Bairro:</label>
                    <input type="text" class="form-control-custom" id="Bairro" name="Bairro" value="{{ old('Bairro') }}" placeholder="Ex: Centro">
                </div>

                {{-- Especialidades --}}
                <div style="margin-bottom: 18px;">
                    <label class="form-label-custom">Especialidades:</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                        @forelse($especialidades as $esp)
                            <label class="form-check-custom" for="esp_{{ $esp->ID_Especialidade }}">
                                <input class="form-check-input" type="checkbox" name="especialidades[]" value="{{ $esp->ID_Especialidade }}" id="esp_{{ $esp->ID_Especialidade }}">
                                <span>{{ $esp->Nome }}</span>
                            </label>
                        @empty
                            <p style="color: var(--muted); font-size: 12px;">Nenhuma especialidade cadastrada ainda.</p>
                        @endforelse
                    </div>
                </div>

                <div style="margin-bottom: 24px;">
                    <label for="Senha" class="form-label-custom">Senha: <span style="color: #dc2626;">*</span></label>
                    <input type="password" class="form-control-custom" id="Senha" name="Senha" required placeholder="Mínimo 6 caracteres">
                </div>

                <button type="submit" class="btn-submit-custom">Concluir Cadastro</button>
            </form>
        </div>
    </div>
</div>
@endsection