@extends('layouts.main')

@section('title', 'Criar Evento - Portal do Artesão')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Cadastrar Novo Evento</h3>
                </div>
                <div class="card-body">
                    <form action="/admin/eventos" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="Nome" class="form-label fw-bold">Nome do Evento: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="Nome" name="Nome" placeholder="Ex: Feira de Artesanato Regional" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="Classificacao" class="form-label fw-bold">Classificação:</label>
                            <input type="text" class="form-control" id="Classificacao" name="Classificacao" placeholder="Ex: Livres, Cerâmica, Esculturas">
                        </div>

                        <div class="form-group mb-3">
                            <label for="Vagas" class="form-label fw-bold">Quantidade de Vagas:</label>
                            <input type="number" class="form-control" id="Vagas" name="Vagas" placeholder="Ex: 50" min="1">
                        </div>

                        <div class="form-group mb-3">
                            <label for="Localizacao" class="form-label fw-bold">Localização:</label>
                            <input type="text" class="form-control" id="Localizacao" name="Localizacao" placeholder="Ex: Praça Central, Estande 12">
                        </div>

                        <div class="form-group mb-3">
                            <label for="Dia" class="form-label fw-bold">Data e Horário do Evento:</label>
                            <input type="datetime-local" class="form-control" id="Dia" name="Dia">
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="/admin/dashboard" class="btn btn-outline-secondary">Voltar ao Painel</a>
                            <button type="submit" class="btn btn-primary">Salvar Evento</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection