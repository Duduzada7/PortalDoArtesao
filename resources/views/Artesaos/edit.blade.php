@extends('layouts.main')

@section('title', 'Editar Artesão - Portal do Artesão')

@section('content')
<div class="col-md-6 offset-md-3 my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Editar Artesão</h4>
            <a href="/artesao" class="btn btn-sm btn-outline-dark">Voltar</a>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/artesao/{{ $artesao->ID_Artesao }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="Nome" class="form-label fw-bold">Nome Completo: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="Nome" name="Nome" value="{{ old('Nome', $artesao->Nome) }}" required>
                </div>

                <div class="mb-3">
                    <label for="Email" class="form-label fw-bold">E-mail: <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="Email" name="Email" value="{{ old('Email', $artesao->Email) }}" required>
                </div>

                <div class="mb-3">
                    <label for="Telefone" class="form-label fw-bold">Telefone: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="Telefone" name="Telefone" value="{{ old('Telefone', $artesao->Telefone) }}" required>
                </div>

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="Rua" class="form-label fw-bold">Rua / Logradouro:</label>
                        <input type="text" class="form-control" id="Rua" name="Rua" value="{{ old('Rua', $artesao->Rua) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="Numero" class="form-label fw-bold">Número:</label>
                        <input type="text" class="form-control" id="Numero" name="Numero" value="{{ old('Numero', $artesao->Numero) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="Bairro" class="form-label fw-bold">Bairro:</label>
                    <input type="text" class="form-control" id="Bairro" name="Bairro" value="{{ old('Bairro', $artesao->Bairro) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Especialidades:</label>
                    <div class="row">
                        @forelse($especialidades as $esp)
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        name="especialidades[]" 
                                        value="{{ $esp->ID_Especialidade }}" 
                                        id="esp_{{ $esp->ID_Especialidade }}"
                                        {{ $artesao->especialidades->contains('ID_Especialidade', $esp->ID_Especialidade) ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label" for="esp_{{ $esp->ID_Especialidade }}">
                                        {{ $esp->Nome }}
                                    </label>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted small ps-3">Nenhuma especialidade cadastrada</p>
                        @endforelse
                    </div>
                </div>

                <button type="submit" class="btn btn-warning w-100 fw-bold">Salvar Alterações</button>
            </form>
        </div>
    </div>
</div>
@endsection