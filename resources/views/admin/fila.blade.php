<table class="table table-hover align-middle mb-0">
    <thead class="table-light">
        <tr>
            <th class="ps-4" style="width: 50px;">#</th>
            <th>ARTESÃO</th>
            <th>BAIRRO</th>
            <th>NÍVEL</th>
            <th class="text-end pe-4">AÇÃO</th>
        </tr>
    </thead>
    <tbody>
        @forelse($artesaos as $index => $artesao)
            <tr>
                <td class="ps-4 fw-bold">
                    <span class="badge rounded-circle p-2 {{ $index == 0 ? 'bg-danger' : 'bg-secondary' }}">
                        {{ $index + 1 }}
                    </span>
                </td>
                <td>
                    <div class="fw-bold">{{ $artesao->Nome }}</div>
                    <small class="text-muted">{{ $artesao->Rua }}{{ $artesao->Numero ? ', ' . $artesao->Numero : '' }}</small>
                </td>
                <td>
                    {{ $artesao->Bairro ?? 'Não informado' }}
                </td>
                <td>
                    <span class="badge bg-light text-dark border p-2 fw-bold">
                        {{ $artesao->Nivel ?? 'Geral' }}
                    </span>
                </td>
                <td class="text-end pe-4">
                    <form action="/admin/fila/mover-final/{{ $artesao->ID_Artesao }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary" title="Mover para o final da fila">
                            &darr;
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center py-4 text-muted">Nenhum artesão aprovado na fila.</td>
            </tr>
        @endforelse
    </tbody>
</table>