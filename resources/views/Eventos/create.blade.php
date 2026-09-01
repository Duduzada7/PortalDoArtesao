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
                    <form action="/admin/eventos" method="POST" id="form-evento">
                        @csrf

                        <!-- NOME DO EVENTO -->
                        <div class="form-group mb-3">
                            <label for="Nome" class="form-label fw-bold">Nome do Evento: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="Nome" name="Nome" value="{{ old('Nome') }}" placeholder="Ex: Feira de Artesanato Regional" required>
                        </div>

                        <!-- DISTRIBUIÇÃO DE VAGAS POR ESPECIALIDADE -->
                        <div class="form-group mb-3 p-3 bg-light rounded border">
                            <label class="form-label fw-bold text-dark mb-2">Especialidades e Vagas:</label>

                            <div class="row g-2 mb-2">
                                <div class="col-md-6">
                                    <select class="form-select" id="especialidade-select">
                                        <option value="">Selecione a Especialidade</option>
                                        @foreach($especialidades as $esp)
                                            <option value="{{ $esp->ID_Especialidade }}" data-nome="{{ $esp->Nome }}">{{ $esp->Nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" id="qtd-vaga-input" placeholder="Qtd Vagas" min="1">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary w-100 fw-bold" type="button" id="btn-add-vagas">+</button>
                                </div>
                            </div>

                            <!-- Lista visual das vagas por especialidade -->
                            <ul class="list-group mb-3" id="lista-vagas-especialidade"></ul>

                            <!-- Totalizador automático de vagas -->
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                <span class="fw-bold">Total Geral de Vagas:</span>
                                <span class="badge bg-primary fs-6" id="total-vagas-badge">0 Vagas</span>
                            </div>

                            <!-- Inputs Ocultos -->
                            <input type="hidden" name="Vagas" id="Vagas" value="{{ old('Vagas', 0) }}">
                            <input type="hidden" name="especialidades_vagas" id="especialidades_vagas_json" value="{{ old('especialidades_vagas', '[]') }}">
                        </div>

                        <!-- LOCALIZAÇÃO PADRÃO ARTESÃO -->
                        <div class="row mb-3">
                            <div class="col-md-8 mb-2 mb-md-0">
                                <label for="Rua" class="form-label fw-bold">Rua / Logradouro:</label>
                                <input type="text" class="form-control" id="Rua" name="Rua" value="{{ old('Rua') }}" placeholder="Ex: Rua das Flores">
                            </div>
                            <div class="col-md-4">
                                <label for="Numero" class="form-label fw-bold">Número:</label>
                                <input type="text" class="form-control" id="Numero" name="Numero" value="{{ old('Numero') }}" placeholder="123">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="Bairro" class="form-label fw-bold">Bairro:</label>
                            <input type="text" class="form-control" id="Bairro" name="Bairro" value="{{ old('Bairro') }}" placeholder="Ex: Centro">
                        </div>

                        <!-- DESCRIÇÃO DO EVENTO -->
                        <div class="form-group mb-3">
                            <label for="Descricao" class="form-label fw-bold">Descrição do Evento:</label>
                            <textarea class="form-control" id="Descricao" name="Descricao" rows="4" placeholder="Descreva os detalhes, regras ou atrações do evento...">{{ old('Descricao') }}</textarea>
                        </div>

                        <!-- INÍCIO E TÉRMINO DO EVENTO -->
                        <div class="row mb-3">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <label for="Dia" class="form-label fw-bold">Data/Hora de Início: <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" id="Dia" name="Dia" value="{{ old('Dia') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="DataFim" class="form-label fw-bold">Data/Hora de Término: <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" id="DataFim" name="DataFim" value="{{ old('DataFim') }}" required>
                            </div>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectEsp = document.getElementById('especialidade-select');
        const inputQtd = document.getElementById('qtd-vaga-input');
        const btnAdd = document.getElementById('btn-add-vagas');
        const listaUI = document.getElementById('lista-vagas-especialidade');
        const totalBadge = document.getElementById('total-vagas-badge');
        const hiddenTotalVagas = document.getElementById('Vagas');
        const hiddenJson = document.getElementById('especialidades_vagas_json');

        let listaVagas = [];
        try {
            listaVagas = JSON.parse(hiddenJson.value) || [];
        } catch(e) {
            listaVagas = [];
        }

        function renderizar() {
            listaUI.innerHTML = '';
            let total = 0;

            listaVagas.forEach((item, index) => {
                total += item.qtd;
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center';
                li.innerHTML = `
                    <span><strong>${item.nome}</strong>: ${item.qtd} vagas</span>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removerItem(${index})">Remover</button>
                `;
                listaUI.appendChild(li);
            });

            totalBadge.innerText = `${total} Vagas`;
            hiddenTotalVagas.value = total;
            hiddenJson.value = JSON.stringify(listaVagas);
        }

        btnAdd.addEventListener('click', function() {
            const id = selectEsp.value;
            const nome = selectEsp.options[selectEsp.selectedIndex]?.getAttribute('data-nome');
            const qtd = parseInt(inputQtd.value);

            if (!id) {
                alert('Selecione uma especialidade.');
                return;
            }
            if (isNaN(qtd) || qtd <= 0) {
                alert('Informe uma quantidade de vagas válida.');
                return;
            }

            const existente = listaVagas.find(i => i.id == id);
            if (existente) {
                existente.qtd += qtd;
            } else {
                listaVagas.push({ id: id, nome: nome, qtd: qtd });
            }

            selectEsp.value = '';
            inputQtd.value = '';
            renderizar();
        });

        window.removerItem = function(index) {
            listaVagas.splice(index, 1);
            renderizar();
        };

        renderizar();
    });
</script>
@endsection