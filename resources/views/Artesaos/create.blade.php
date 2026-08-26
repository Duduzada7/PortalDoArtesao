@extends('layouts.main')

@section('title', 'Cadastrar Artesão - Portal do Artesão')

@section('content')
<div class="col-md-6 offset-md-3 my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Cadastro de Artesão</h4>
        </div>
        <div class="card-body">
            {{-- Exibição de erros de validação --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Formulário que enviará os dados via POST para a rota do store() --}}
            <form action="/artesao/cadastrar" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="Nome" class="form-label fw-bold">Nome Completo: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="Nome" name="Nome" value="{{ old('Nome')}}" required placeholder= "Ex: Maria Silva">
                </div>
                <div class="mb-3">
                    <label for="Email" class="form-label fw-bold">E-mail: <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="Email" name="Email" value="{{ old('Email')}}" required placeholder= "exemplo@email.com">
                </div>
                <div class="mb-3">
                    <label for="Telefone" class="form-label fw-bold">Telefone: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="Telefone" name="Telefone" value="{{ old('Telefone')}}" required placeholder= "(31) 99999-9999">
                </div>
                <div class="mb-3">
                    <label for="Endereco" class="form-label fw-bold">Endereço/Cidade: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="Endereco" name="Endereco" value="{{ old('Endereco')}}" required placeholder= "Ex: Bairro Centro, Congonhas - MG">
                </div>

                {{-- Especialidades --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Especialidades:</label>
                    <div class="row">
                        @forelse($especialidades as $esp)
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="especialidades[]" value="{{ $esp->ID_Especialidade }}" id="esp_{{ $esp->ID_Especialidade }}">
                                    <label class="form-check-label" for="esp_{{ $esp->ID_Especialidade }}">{{ $esp->Nome }}
                                    </label>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted samll ps-3">Nenhuma especialidade cadastrada ainda</p>
                        @endforelse
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
            </form>
        </div>
    </div>
</div>
@endsection