<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;

class EventController extends Controller
{


   public function create()
{
    if (session('user_type') !== 'adm') {
        return redirect('/login')->with('error', 'Acesso permitido apenas para administradores.');
    }

    return view('Eventos.create'); // Ajuste o caminho de acordo com o nome da sua view (ex: eventos.create)
}
    public function delete(){
        return view('Eventos.delete');
    }
    public function list(){
    $eventos = Evento::all();
    return view('Eventos.list', ['eventos' => $eventos]);
}
    public function store(Request $request)
{
    if (session('user_type') !== 'adm') {
        return redirect('/login')->with('error', 'Acesso negado.');
    }

    $request->validate([
        'Nome' => 'required|string|max:255',
        'Classificacao' => 'nullable|string|max:100',
        'Vagas' => 'nullable|integer',
        'Localizacao' => 'nullable|string|max:255',
        'Dia' => 'nullable|date',
    ]);

    $evento = new Evento();
    $evento->Nome = $request->Nome;
    $evento->Classificacao = $request->Classificacao;
    $evento->Vagas = $request->Vagas;
    $evento->Localizacao = $request->Localizacao;
    $evento->Dia = $request->Dia;
    $evento->idADM = session('user_id'); // Vincula o ID do Admin logado na sessão

    $evento->save();

    return redirect('/admin/dashboard')->with('msg', 'Evento criado com sucesso!');
}
public function index()
{
    if (session('user_type') !== 'adm') {
        return redirect('/login')->with('error', 'Acesso restrito.');
    }

    $eventos = Evento::all();

    // Mudando a chave para 'events' para bater com a sua view
    return view('Eventos.list', ['eventos' => $eventos]); 
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