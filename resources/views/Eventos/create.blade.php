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

                        <div class="form-group mb-3">
                            <label for="Nome" class="form-label fw-bold">Nome do Evento: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="Nome" name="Nome" placeholder="Ex: Feira de Artesanato Regional" required>
                        </div>

                        <!-- CLASSIFICAÇÃO / TAGS -->
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Classificação do Evento:</label>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" id="classificacao-input" placeholder="Ex: Feira Geral, Exposição Local">
                                <button class="btn btn-outline-primary" type="button" id="btn-add-classificacao">+</button>
                            </div>
                            <div id="container-tags" class="d-flex flex-wrap gap-2 my-2"></div>
                            <input type="hidden" name="Classificacao" id="Classificacao">
                        </div>

                        <!-- DISTRIBUIÇÃO DE VAGAS POR ESPECIALIDADE -->
                        <div class="form-group mb-3 p-3 bg-light rounded border">
                            <label class="form-label fw-bold text-dark">Distribuição de Vagas por Especialidade:</label>
                            <div class="row g-2 mb-2">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="especialidade-vaga-input" placeholder="Especialidade (ex: Cerâmica)">
                                </div>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" id="qtd-vaga-input" placeholder="Qtd Vagas" min="1">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-success w-100" type="button" id="btn-add-vagas">+</button>
                                </div>
                            </div>

                            <!-- Lista visual das vagas por especialidade -->
                            <ul class="list-group mb-3" id="lista-vagas-especialidade"></ul>

                            <!-- Totalizador automático de vagas -->
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Total Geral de Vagas:</span>
                                <span class="badge bg-primary fs-6" id="total-vagas-badge">0 Vagas</span>
                            </div>

                            <!-- Campo oculto com a quantidade total (para a coluna Vagas do banco) -->
                            <input type="hidden" name="Vagas" id="Vagas" value="0">
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- LOGICA DE CLASSIFICAÇÕES/TAGS ---
        const inputClass = document.getElementById('classificacao-input');
        const btnAddClass = document.getElementById('btn-add-classificacao');
        const containerTags = document.getElementById('container-tags');
        const hiddenClassificacao = document.getElementById('Classificacao');
        let listaClassificacoes = [];

        function atualizarTags() {
            containerTags.innerHTML = '';
            listaClassificacoes.forEach((item, index) => {
                const badge = document.createElement('span');
                badge.className = 'badge bg-secondary p-2 d-flex align-items-center gap-2';
                badge.innerHTML = `${item} <button type="button" class="btn-close btn-close-white" style="font-size:0.65rem;" onclick="removerTag(${index})"></button>`;
                containerTags.appendChild(badge);
            });
            hiddenClassificacao.value = listaClassificacoes.join(', ');
        }

        btnAddClass.addEventListener('click', function() {
            const val = inputClass.value.trim();
            if (val && !listaClassificacoes.includes(val)) {
                listaClassificacoes.push(val);
                inputClass.value = '';
                atualizarTags();
            }
        });

        window.removerTag = function(index) {
            listaClassificacoes.splice(index, 1);
            atualizarTags();
        };

        // --- LÓGICA DE DISTRIBUIÇÃO DE VAGAS ---
        const espInput = document.getElementById('especialidade-vaga-input');
        const qtdInput = document.getElementById('qtd-vaga-input');
        const btnAddVagas = document.getElementById('btn-add-vagas');
        const listaVagas = document.getElementById('lista-vagas-especialidade');
        const totalBadge = document.getElementById('total-vagas-badge');
        const hiddenVagas = document.getElementById('Vagas');

        let distribuicaoVagas = [];

        function atualizarVagas() {
            listaVagas.innerHTML = '';
            let total = 0;

            distribuicaoVagas.forEach((item, index) => {
                total += item.qtd;
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center';
                li.innerHTML = `
                    <span><strong>${item.especialidade}</strong>: ${item.qtd} vagas</span>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removerVaga(${index})">Remover</button>
                `;
                listaVagas.appendChild(li);
            });

            totalBadge.innerText = `${total} Vagas`;
            hiddenVagas.value = total;
        }

        btnAddVagas.addEventListener('click', function() {
            const esp = espInput.value.trim();
            const qtd = parseInt(qtdInput.value);

            if (esp && qtd > 0) {
                distribuicaoVagas.push({ especialidade: esp, qtd: qtd });
                espInput.value = '';
                qtdInput.value = '';
                atualizarVagas();
            }
        });

        window.removerVaga = function(index) {
            distribuicaoVagas.splice(index, 1);
            atualizarVagas();
        };
    });
</script>
@endsection