@extends('layouts.admin')

@section('title', 'Criar Evento')

@section('content')
<div class="page">
    <p class="crumbs">Administração <span aria-hidden="true">→</span> Eventos <span aria-hidden="true">→</span> Novo</p>

    <div class="form-card">
        <h1 class="form-card__title">Criar Novo Evento</h1>

        @if ($errors->any())
            <div class="flash flash--error" role="alert">
                <ul class="error-list">
                    @foreach ($errors->all() as $erro)
                        <li>{{ $erro }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/admin/eventos" method="POST" id="form-evento">
            @csrf

            <!-- NOME DO EVENTO -->
            <div class="field">
                <label for="Nome">Nome do Evento <span class="req">*</span></label>
                <input type="text" id="Nome" name="Nome" value="{{ old('Nome') }}" placeholder="Ex: Feira de Artesanato Regional" required>
            </div>

            <!-- INÍCIO E TÉRMINO DO EVENTO -->
            <div class="grid-2">
                <div class="field">
                    <label for="Dia">Data/Hora de Início <span class="req">*</span></label>
                    <input type="datetime-local" id="Dia" name="Dia" value="{{ old('Dia') }}" required>
                </div>
                <div class="field">
                    <label for="DataFim">Data/Hora de Término <span class="req">*</span></label>
                    <input type="datetime-local" id="DataFim" name="DataFim" value="{{ old('DataFim') }}" required>
                </div>
            </div>

            <!-- LOCALIZAÇÃO -->
            <div class="grid-2">
                <div class="field">
                    <label for="Rua">Rua / Logradouro</label>
                    <input type="text" id="Rua" name="Rua" value="{{ old('Rua') }}" placeholder="Ex: Rua das Flores">
                </div>
                <div class="field">
                    <label for="Numero">Número</label>
                    <input type="text" id="Numero" name="Numero" value="{{ old('Numero') }}" placeholder="123">
                </div>
            </div>

            <div class="field">
                <label for="Bairro">Bairro</label>
                <input type="text" id="Bairro" name="Bairro" value="{{ old('Bairro') }}" placeholder="Ex: Centro">
            </div>

            <!-- DISTRIBUIÇÃO DE VAGAS POR ESPECIALIDADE -->
            <div class="vagas">
                <p class="vagas__title">Especialidades e Vagas</p>

                <div class="vagas__row">
                    <select id="especialidade-select" aria-label="Especialidade">
                        <option value="">Selecione a Especialidade</option>
                        @foreach($especialidades as $esp)
                            <option value="{{ $esp->ID_Especialidade }}" data-nome="{{ $esp->Nome }}">{{ $esp->Nome }}</option>
                        @endforeach
                    </select>
                    <input type="number" id="qtd-vaga-input" placeholder="Qtd. de vagas" min="1" aria-label="Quantidade de vagas">
                    <button type="button" id="btn-add-vagas" class="vagas__add" aria-label="Adicionar especialidade">+</button>
                </div>

                <!-- Lista visual das vagas por especialidade -->
                <ul class="vagas__list" id="lista-vagas-especialidade"></ul>

                <!-- Totalizador automático de vagas -->
                <div class="vagas__total">
                    <span>Total geral de vagas</span>
                    <span class="total-badge" id="total-vagas-badge">0 Vagas</span>
                </div>

                <!-- Inputs Ocultos -->
                <input type="hidden" name="Vagas" id="Vagas" value="{{ old('Vagas', 0) }}">
                <input type="hidden" name="especialidades_vagas" id="especialidades_vagas_json" value="{{ old('especialidades_vagas', '[]') }}">
            </div>

            <!-- DESCRIÇÃO DO EVENTO -->
            <div class="field">
                <label for="Descricao">Descrição do Evento</label>
                <textarea id="Descricao" name="Descricao" rows="4" placeholder="Descreva os detalhes, regras ou atrações do evento...">{{ old('Descricao') }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">Criar Evento</button>
                <a href="/admin/eventos" class="btn-cancel">Cancelar</a>
            </div>
        </form>
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
                li.className = 'vagas__item';
                li.innerHTML = `
                    <span><strong>${item.nome}</strong>: ${item.qtd} vagas</span>
                    <button type="button" class="vagas__remove" onclick="removerItem(${index})">Remover</button>
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