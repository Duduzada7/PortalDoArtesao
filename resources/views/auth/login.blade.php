@extends('layouts.main')

@section('title', 'Login - Portal do Artesão')

@section('content')
<div class="col-md-4 offset-md-4 my-5">
    <h2 class="text-center mb-4">Acesso ao Portal</h2>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="/login" method="POST">
        @csrf
        
        <div class="form-group mb-3">
            <label for="tipo_usuario" class="fw-bold">Acessar como:</label>
            <select name="tipo_usuario" id="tipo_usuario" class="form-control" required>
                <option value="adm" selected>Administrador</option>
                <option value="artesao">Artesão</option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="seu@email.com" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>
</div>
@endsection