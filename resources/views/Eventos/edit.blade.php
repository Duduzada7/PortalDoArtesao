@extends('layouts.main')

@section('title', 'Editar Evento - Portal do Artesão')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h3 class="mb-0">Editar Evento</h3>
                </div>
                <div class="card-body">
                    <form action="/admin/eventos/{{ $evento->ID_Evento }}" method="POST" id="form-evento">
                        @csrf
                        @method('PUT')

                        <!-- NOME DO EVENTO -->
                        <div class="form-group mb-3">
                            <label for="Nome" class="form-label fw-bold">Nome do Evento: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="Nome" name="Nome" value="{{ old('Nome', $evento->Nome) }}" required>
                        </div>

                       <!-- DISTRIBUIÇÃO DE VAGAS POR ESPECIALIDADE -->
<div class="form-group mb-3 p-3 bg-light rounded border">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <label class="form-label fw-bold text-dark mb-0">Especialidades e Vagas:</label>
        <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalNovaEspecialidade">
            + Cadastrar Nova Especialidade
        </button>
    </div>

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

    <ul class="list-group mb-3" id="lista-vagas-especialidade"></ul>

    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
        <span class="fw-bold">Total Geral de Vagas:</span>
        <span class="badge bg-primary fs-6" id="total-vagas-badge">0 Vagas</span>
    </div>

    <input type="hidden" name="Vagas" id="Vagas" value="{{ old('Vagas', 0) }}">
    <input type="hidden" name="especialidades_vagas" id="especialidades_vagas_json" value="{{ old('especialidades_vagas', '[]') }}">
</div>

<!-- MODAL PARA CADASTRAR NOVA ESPECIALIDADE -->
<div class="modal fade" id="modalNovaEspecialidade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cadastrar Nova Especialidade</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="nome-nova-especialidade" class="form-label fw-bold">Nome da Especialidade:</label>
                    <input type="text" class="form-control" id="nome-nova-especialidade" placeholder="Ex: Marcenaria, Saboaria...">
                    <div class="invalid-feedback" id="erro-especialidade"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btn-salvar-especialidade">Cadastrar e Selecionar</button>
            </div>
        </div>
    </div>
</div>
                        <!-- LOCALIZAÇÃO PADRÃO ARTESÃO -->
                        <div class="row mb-3">
                            <div class="col-md-8 mb-2 mb-md-0">
                                <label for="Rua" class="form-label fw-bold">Rua / Logradouro:</label>
                                <input type="text" class="form-control" id="Rua" name="Rua" value="{{ old('Rua', $evento->Rua) }}" placeholder="Ex: Rua das Flores">
                            </div>
                            <div class="col-md-4">
                                <label for="Numero" class="form-label fw-bold">Número:</label>
                                <input type="text" class="form-control" id="Numero" name="Numero" value="{{ old('Numero', $evento->Numero) }}" placeholder="123">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="Bairro" class="form-label fw-bold">Bairro:</label>
                            <input type="text" class="form-control" id="Bairro" name="Bairro" value="{{ old('Bairro', $evento->Bairro) }}" placeholder="Ex: Centro">
                        </div>

                        <!-- DESCRIÇÃO DO EVENTO -->
                        <div class="form-group mb-3">
                            <label for="Descricao" class="form-label fw-bold">Descrição do Evento:</label>
                            <textarea class="form-control" id="Descricao" name="Descricao" rows="4" placeholder="Descreva os detalhes, regras ou atrações do evento...">{{ old('Descricao', $evento->Descricao) }}</textarea>
                        </div>

                        <!-- INÍCIO E TÉRMINO DO EVENTO -->
                        <div class="row mb-3">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <label for="Dia" class="form-label fw-bold">Data/Hora de Início: <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" id="Dia" name="Dia" value="{{ old('Dia', $evento->Dia ? date('Y-m-d\TH:i', strtotime($evento->Dia)) : '') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="DataFim" class="form-label fw-bold">Data/Hora de Término: <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" id="DataFim" name="DataFim" value="{{ old('DataFim', $evento->DataFim ? date('Y-m-d\TH:i', strtotime($evento->DataFim)) : '') }}" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="/admin/dashboard" class="btn btn-outline-secondary">Voltar ao Painel</a>
                            <button type="submit" class="btn btn-success">Salvar Alterações</button>
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
    document.getElementById('btn-salvar-especialidade').addEventListener('click', function() {
    const inputNome = document.getElementById('nome-nova-especialidade');
    const erroDiv = document.getElementById('erro-especialidade');
    const nome = inputNome.value.trim();

    if (!nome) {
        inputNome.classList.add('is-invalid');
        erroDiv.innerText = 'Por favor, informe o nome da especialidade.';
        return;
    }

    fetch('/admin/especialidades', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ Nome: nome })
    })
    .then(response => {
        if (!response.ok) throw response;
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Adiciona a nova opção no <select>
            const select = document.getElementById('especialidade-select');
            const option = document.createElement('option');
            option.value = data.especialidade.ID_Especialidade;
            option.setAttribute('data-nome', data.especialidade.Nome);
            option.text = data.especialidade.Nome;
            option.selected = true; // Já deixa selecionada
            select.appendChild(option);

            // Limpa e fecha o modal
            inputNome.value = '';
            inputNome.classList.remove('is-invalid');
            const modalEl = document.getElementById('modalNovaEspecialidade');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
        }
    })
    .catch(async err => {
        if (err.json) {
            const errData = await err.json();
            inputNome.classList.add('is-invalid');
            erroDiv.innerText = errData.errors?.Nome?.[0] || 'Erro ao cadastrar especialidade.';
        }
    });
});
</script>

@endsection