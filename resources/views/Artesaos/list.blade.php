@extends('layouts.main')

@section('title', 'Lista de Artesãos')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Artesãos Cadastrados</h2>

    <div class="row">
        @forelse($artesaos as $artesao)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title">{{ $artesao->Nome }}</h4>
                        <p class="card-text text-muted mb-1">
                            <strong>Telefone:</strong> {{ $artesao->Telefone }}
                        </p>
                        <p class="card-text text-muted mb-3">
                            <strong>Email:</strong> {{ $artesao->Email }}
                        </p>

                        <h6>Especialidades:</h6>
                        <ul class="mb-0">
                            @forelse($artesao->especialidades as $esp)
                                <li>{{ $esp->Nome }}</li>
                            @empty
                                <li class="text-muted fst-italic">Nenhuma especialidade informada</li>
                            @endforelse
                        </ul>
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

@foreach($artesaos as $artesao)
    <div class="card mb-3">
        <h3>{{ $artesao->Nome }}</h3>
        <p>Telefone: {{ $artesao->Telefone }}</p>

        <!-- como usamos with('especialidade'), já temos acesso direto aqui: -->
         <p>Especialidades:</p>
         <ul>
            @foreach($artesao->especialidades as $esp)
                <li>{{ $esp->Nome }}</li>
            @endforeach
         </ul>
    </div>
@endforeach