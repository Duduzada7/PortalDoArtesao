<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Especialidades;

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
            'Classificacao' => 'nullable|string|max:100',
            'Vagas' => 'nullable|integer',
            'Localizacao' => 'nullable|string|max:255',
            'Dia' => 'nullable|date',
            'DataFim' => 'nullable|date|after_or_equal:Dia',
            'ID_Especialidade' => 'nullable|integer',
        ]);

        $evento = new Evento();
        $evento->Nome = $request->Nome;
        $evento->Classificacao = $request->Classificacao;
        $evento->Vagas = $request->Vagas;
        $evento->Localizacao = $request->Localizacao;
        $evento->Dia = $request->Dia;
        $evento->DataFim = $request->DataFim;
        $evento->ID_Especialidade = $request->ID_Especialidade;
        $evento->idADM = session('user_id');
    $evento->Evento = $request->Evento;
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
}