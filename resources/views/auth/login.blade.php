@extends('layouts.main')

@section('title', 'Login - Portal do Artesão')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <!-- ALERTAS DE ERRO / SUCESSO -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('msg'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('msg') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4 fw-bold">Acesso ao Portal</h3>

                    <form action="/login" method="POST">
                        @csrf

                        <!-- CAMPO E-MAIL -->
                        <div class="form-group mb-3">
                            <label for="Email" class="form-label fw-bold">E-mail:</label>
                            <input type="email" class="form-control" id="Email" name="Email" placeholder="seu@email.com" value="{{ old('Email') }}" required>
                        </div>

                        <!-- CAMPO SENHA -->
                        <div class="form-group mb-3">
                            <label for="Senha" class="form-label fw-bold">Senha:</label>
                            <input type="password" class="form-control" id="Senha" name="Senha" placeholder="Digite sua senha" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mt-2">Entrar</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection