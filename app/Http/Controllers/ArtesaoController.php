<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artesao;
use App\Models\Evento;
use App\Models\Especialidades;


class ArtesaoController extends Controller{

    //Exibe a listagem publica de artesaos (ou busca)

    public function index(){
        //busca apenas artesãos aprovados (se usar status)
        $artesaos = Artesao::with('especialidades')->get();

        return view('Artesaos.list', compact('artesaos'));
    }

    //Exibe o formulario de cadastro de artesão
    public function create(){
        //busca todas as especialidades cadastradas para o artesão escolher
        $especialidades = Especialidades::all();

        //retorna a view 'Artesaos.create' passando as especialidades
        return view('Artesaos.create', compact('especialidades'));
    }

    //Salva um artesão no banco
    public function store(Request $request){
        //validacao de dados
        $request->validate([
            'Nome'      => 'required|string|max:255',
            'Email'     => 'required|email|unique:artesao,Email',
            'Telefone'  => 'required|string|max:20',
            'Endereco'  => 'nullable|string|max>255',
            'especialidades' => 'nullable|array',
        ], [
            'Email.unique'  =>  'Este e-mail já está cadastrado.'
        ]);

        //criacao do registro
        $artesao = new Artesao();
        $artesao->Nome = $request->Nome;
        $artesao->Email = $request->Email;
        $artesao->Telefone = $request->Telefone;
        $artesao->Endereco = $request->Endereco;
        $artesao->StatusAprovacao = 'Pendente'; //status inicial
        $artesao->save();

        //salva as especialidades selecionadas na tabela pivô

        if($request->has('especialidades')){
            $artesao->especialidades()->attach($request->especialidades);
        }

        return redirect('/login')->with('msg', 'Cadastro realizado com sucesso! Aguarde a aprovação.');
    }

    //update: exibe o formulário de edição
    public function edit($id){
        $artesao = Artesao::with('especialidades')->findOrFail($id);
        $especialidades = Especialidaes::all();

        return view('Artesaos.edit', compact('artesao', 'especialidades'));
    }

    //update: salva as alterações do artesão
    public function update(Request $request, $id){
        $artesao = Artesao::findOrFail($id);

        $request->validate([
            'Nome'           => 'required|string|max:255',
            'Email'          => 'required|email|unique:artesao,Email,' . $id . ',ID_Artesao',
            'Telefone'       => 'required|string|max:20',
            'Endereco'       => 'nullable|string|max:255',
            'especialidades' => 'nullable|array',
        ], [
            'Email.unique'   => 'Este e-mail já está cadastrado por outro artesão.'
        ]);

        $artesao->Nome = $request->Nome;
        $artesao->Email = $request->Email;
        $artesao->Telefone = $request->Telefone;
        $artesao->Endereco = $request->Endereco;
        $artesao->save();

        //atualiza as especialidades na tablea pivô (sync substitui os vínculos antigos)
        $artesao->especialidades()->sync($request->input('especialidades', []));

        return redirect('/artesao')->with('msg', 'Artesão atualizado com sucesso!');
    }

    //delete: remove o artesão
    public function destroy($id){
        $artesao = Artesao::findOrFail($id);
        $artesao->delete();

        return redirect('/artesao')->with('msg', 'Artesão removido com sucesso!');
    }

    //painel/dashboard do artesao logado
    public function dashboard(){
        //protecao de rota via sessao (mesmo padrao usado no AuthController)
        if(session('user_type') !== 'artesao'){
            return redirect('/login')->with('error', 'Acesso negado');
        }

        $artesao = Artesao::with('eventos')->findOrFail(session('user_id'));
        $eventosDisponiveis = Evento::all();

        return view('artesao.dashboard', compact('artesao', 'eventosDisponiveis'));
    }

    //candidatar-se a um evento
    public function candidatar(Request $request, $eventoId){
        if(session('user_type') !== 'artesao'){
            return redirect('/login')->with('error', 'Acesso negado');
        }

        $artesao = Artesao::findOrFail(session('user_id'));

        //vincula o evento ao artesao na tablea pivô 'candidatura'
        $artesao->eventos()->syncWithoutDetaching([
            $eventoId => ['StatusDaCandidatura' => 'Inscrito']
        ]);

        return redirect('/artesao/dashboard')->with('msg', 'Candidatura enviada com sucesso!');
    }
}