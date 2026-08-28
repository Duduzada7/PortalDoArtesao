@extends('layouts.main')

@section('title', 'Lista de Artesãos')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Artesãos Cadastrados</h2>
        <a href="/artesao/cadastrar" class="btn btn-primary">Cadastrar Novo</a>
    </div>

    @if(session('msg'))
        <div class="alert alert-success">
            {{ session('msg') }}
        </div>
    @endif

    <div class="row">
        @forelse($artesaos as $artesao)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h4 class="card-title">{{ $artesao->Nome }}</h4>
                        <p class="card-text text-muted mb-1">
                            <strong>Telefone:</strong> {{ $artesao->Telefone }}
                        </p>
                        <p class="card-text text-muted mb-1">
                            <strong>Email:</strong> {{ $artesao->Email }}
                        </p>
                        <p class="card-text text-muted mb-3">
<strong>Endereço:</strong> {{ $artesao->Rua ? $artesao->Rua . ', ' . $artesao->Numero . ' - ' . $artesao->Bairro : 'Não informado' }}                        </p>

                        <h6>Especialidades:</h6>
                        <ul class="mb-3">
                            @forelse($artesao->especialidades as $esp)
                                <li>{{ $esp->Nome }}</li>
                            @empty
                                <li class="text-muted fst-italic">Nenhuma especialidade informada</li>
                            @endforelse
                        </ul>

                        <div class="mt-auto pt-2 border-top d-flex gap-2">
                            <a href="/artesao/editar/{{ $artesao->ID_Artesao }}" class="btn btn-sm btn-outline-warning w-50">Editar</a>
                            <form action="/artesao/{{ $artesao->ID_Artesao }}" method="POST" class="w-50" onsubmit="return confirm('Tem certeza que deseja excluir este artesão?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100">Excluir</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    Nenhum artesão cadastrado no momento.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection