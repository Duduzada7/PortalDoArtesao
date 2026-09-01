<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Artesao;
use App\Models\Evento;
use App\Models\Especialidades;

class ArtesaoController extends Controller
{
    // Exibe a listagem pública de artesãos
    public function index()
    {
        $artesaos = Artesao::with('especialidades')->get();
        return view('Artesaos.list', compact('artesaos'));
    }

    // Exibe o formulário de cadastro de artesão
    public function create()
    {
        $especialidades = Especialidades::all();
        return view('Artesaos.create', compact('especialidades'));
    }

    // Salva um artesão no banco
    public function store(Request $request)
{
    // 1. Adicionamos a Senha nas regras de validação
    $request->validate([
        'Nome'           => 'required|string|max:255',
        'Email'          => 'required|email|unique:artesao,Email',
        'Senha'          => 'required|string|min:6', // <-- Adicionado
        'Telefone'       => 'required|string|max:20',
        'Rua'            => 'nullable|string|max:255',
        'Numero'         => 'nullable|string|max:20',
        'Bairro'         => 'nullable|string|max:255',
        'especialidades' => 'nullable|array',
    ], [
        'Email.unique'   => 'Este e-mail já está cadastrado.',
        'Senha.min'      => 'A senha deve ter no mínimo 6 caracteres.'
    ]);

    // 2. Criação do registro guardando o Hash da Senha
    $artesao = new Artesao();
    $artesao->Nome = $request->Nome;
    $artesao->Email = $request->Email;
    $artesao->Senha = Hash::make($request->Senha); // <-- Adicionado com Hash::make()
    $artesao->Telefone = $request->Telefone;
    $artesao->Rua = $request->Rua;
    $artesao->Numero = $request->Numero;
    $artesao->Bairro = $request->Bairro;
    $artesao->StatusAprovacao = 'pendente';
    $artesao->save();

    // Salva as especialidades selecionadas na tabela pivô
    if ($request->has('especialidades')) {
        $artesao->especialidades()->attach($request->especialidades);
    }

    return redirect('/login')->with('msg', 'Cadastro realizado com sucesso! Aguarde a aprovação.');
}

    // Form de Edição
    public function edit($id)
    {
        $artesao = Artesao::with('especialidades')->findOrFail($id);
        $especialidades = Especialidades::all(); // Corrigido erro de digitação anterior

        return view('Artesaos.edit', compact('artesao', 'especialidades'));
    }

    // Salva atualizações do artesão
    public function update(Request $request, $id)
    {
        $artesao = Artesao::findOrFail($id);

        $request->validate([
            'Nome'           => 'required|string|max:255',
            'Email'          => 'required|email|unique:artesao,Email,' . $id . ',ID_Artesao',
            'Telefone'       => 'required|string|max:20',
            'Rua'            => 'nullable|string|max:255',
            'Numero'         => 'nullable|string|max:20',
            'Bairro'         => 'nullable|string|max:255',
            'especialidades' => 'nullable|array',
        ], [
            'Email.unique'   => 'Este e-mail já está cadastrado por outro artesão.'
        ]);

        $artesao->Nome = $request->Nome;
        $artesao->Email = $request->Email;
        $artesao->Telefone = $request->Telefone;
        $artesao->Rua = $request->Rua;
        $artesao->Numero = $request->Numero;
        $artesao->Bairro = $request->Bairro;
        if ($request->filled('Senha')) {
    $artesao->Senha = Hash::make($request->Senha);
}
        $artesao->save();

        $artesao->especialidades()->sync($request->input('especialidades', []));
    
        return redirect('/artesao')->with('msg', 'Artesão atualizado com sucesso!');
    }

    // Remove o artesão
    public function destroy($id)
    {
        $artesao = Artesao::findOrFail($id);
        $artesao->delete();

        return redirect('/artesao')->with('msg', 'Artesão removido com sucesso!');
    }

    // Dashboard do artesão logado
    public function dashboard()
{
    if (session('user_type') !== 'artesao') {
        return redirect('/login')->with('error', 'Acesso negado');
    }

    $artesao = Artesao::with('eventos')->findOrFail(session('user_id'));

    // Garante que nem por sessão aberta ele acessa se tiver pendente/recusado
    if ($artesao->StatusAprovacao !== 'aprovado') {
        session()->forget(['user_id', 'user_name', 'user_type']);
        return redirect('/login')->with('error', 'Sua conta ainda não foi aprovada pelo administrador.');
    }

    $eventosDisponiveis = Evento::all();

    return view('Artesaos.dashboard', compact('artesao', 'eventosDisponiveis'));
}

    // Candidatura em evento
    public function candidatarEvento($id)
{
    $artesaoId = session('user_id');
    $artesao = Artesao::findOrFail($artesaoId);

    // Salva o registro na tabela pivô 'candidatura' com status inicial 'Inscrito'
    $artesao->eventos()->attach($id, [
        'StatusDaCandidatura' => 'Inscrito'
    ]);

    return redirect()->back()->with('msg', 'Candidatura enviada! Aguarde a aprovação do administrador.');
}
}