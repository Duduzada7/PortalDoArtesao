<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Especialidades;
use Illuminate\Support\Facades\DB;
use App\Models\Artesao;

class EventController extends Controller
{
    public function index()
    {
        if (session('user_type') !== 'adm') {
            return redirect('/login')->with('error', 'Acesso restrito.');
        }

        $eventos = Evento::all();
        return view('Eventos.list', ['eventos' => $eventos]);
    }

    public function list()
    {
        $eventos = Evento::all();
        return view('Eventos.list', ['eventos' => $eventos]);
    }

    public function create()
    {
        if (session('user_type') !== 'adm') {
            return redirect('/login')->with('error', 'Acesso permitido apenas para administradores.');
        }

        // Carrega especialidades do banco para o select
        $especialidades = Especialidades::all();

        return view('Eventos.create', compact('especialidades'));
    }

    public function store(Request $request)
    {
        if (session('user_type') !== 'adm') {
            return redirect('/login')->with('error', 'Acesso negado.');
        }

        $request->validate([
            'Nome' => 'required|string|max:255',
            'Descricao' => 'nullable|string',
            'Vagas' => 'required|integer',
            'Dia' => 'required|date',
            'DataFim' => 'nullable|date|after_or_equal:Dia',
            'Rua' => 'nullable|string|max:255',
            'Bairro' => 'nullable|string|max:255',
            'Numero' => 'nullable|string|max:50',
        ]);

        $evento = new Evento();
        $evento->Nome = $request->Nome;
        $evento->Descricao = $request->Descricao;
        $evento->Vagas = $request->Vagas;
        $evento->Dia = $request->Dia;
        $evento->DataFim = $request->DataFim;
        $evento->Rua = $request->Rua;
        $evento->Bairro = $request->Bairro;
        $evento->Numero = $request->Numero;
        $evento->idADM = session('user_id');

        $evento->save();

        return redirect('/admin/dashboard')->with('msg', 'Evento criado com sucesso!');
    }

    // TELA DE EDIÇÃO DO EVENTO
    public function edit($id)
    {
        if (session('user_type') !== 'adm') {
            return redirect('/login')->with('error', 'Acesso negado.');
        }

        $evento = Evento::findOrFail($id);
        $especialidades = Especialidades::all();

        return view('Eventos.edit', compact('evento', 'especialidades'));
    }

    // PROCESSAR EDIÇÃO DO EVENTO
    public function update(Request $request, $id)
    {
        if (session('user_type') !== 'adm') {
            return redirect('/login')->with('error', 'Acesso negado.');
        }

        $request->validate([
            'Nome' => 'required|string|max:255',
            'Descricao' => 'nullable|string',
            'Classificacao' => 'nullable|string|max:100',
            'Vagas' => 'nullable|integer',
            'Localizacao' => 'nullable|string|max:255',
            'Dia' => 'nullable|date',
            'DataFim' => 'nullable|date|after_or_equal:Dia',
            'ID_Especialidade' => 'nullable|integer',
        ]);

        $evento = Evento::findOrFail($id);
        $evento->Nome = $request->Nome;
        $evento->Classificacao = $request->Classificacao;
        $evento->Vagas = $request->Vagas;
        $evento->Localizacao = $request->Localizacao;
        $evento->Dia = $request->Dia;
        $evento->DataFim = $request->DataFim;
        $evento->ID_Especialidade = $request->ID_Especialidade;
        $evento->Descricao = $request->Descricao;
        $evento->save();

        return redirect('/admin/eventos')->with('msg', 'Evento atualizado com sucesso!');
    }

    public function destroy($id)
    {
        if (session('user_type') !== 'adm') {
            return redirect('/login')->with('error', 'Acesso negado.');
        }

        Evento::findOrFail($id)->delete();

        return redirect('/admin/eventos')->with('msg', 'Evento excluído com sucesso!');
    }

    public function encerrar($id)
    {
        if (session('user_type') !== 'adm') {
            return redirect('/login')->with('error', 'Acesso negado.');
        }

        $evento = Evento::findOrFail($id);

        if ($evento->status === 'encerrado') {
            return redirect()->back()->with('error', 'Este evento já está encerrado.');
        }

        DB::transaction(function () use ($evento) {
            // 1. Marca o evento como encerrado
            $evento->status = 'encerrado';
            $evento->save();

            // 2. Busca os artesãos vinculados ao evento
            // Ajuste 'artesaos' se o seu relacionamento no Model Evento tiver outro nome
            $artesaoIds = $evento->artesaos()->pluck('artesao.ID_Artesao');

            // 3. Atualiza a data da última participação dos participantes
            if ($artesaoIds->isNotEmpty()) {
                Artesao::whereIn('ID_Artesao', $artesaoIds)->update([
                    'DataUltimaParticipacao' => now()
                ]);
            }

            // 4. Reordena TODOS os artesãos APROVADOS:
            // Quens nunca participaram (DataUltimaParticipacao NULL) ficam no topo.
            // Quem já participou fica no final, ordenado pela data mais antiga.
            $artesaosOrdenados = Artesao::where('StatusAprovacao', 'aprovado')
                ->orderByRaw('DataUltimaParticipacao IS NULL DESC')
                ->orderBy('DataUltimaParticipacao', 'asc')
                ->get();

            foreach ($artesaosOrdenados as $index => $artesao) {
                $artesao->posicao_fila = $index + 1;
                $artesao->save();
            }
        });

        return redirect('/admin/fila')->with('msg', 'Evento encerrado e fila de prioridade reordenada com sucesso!');
    }
}