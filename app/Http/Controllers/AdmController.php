<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Http\Request;
use App\Models\Adm; 

class AdmController extends Controller
{
 
    public function index()
    {
        if (session('user_type') !== 'adm') {
            return redirect('/login')->with('error', 'Acesso negado.');
        }

        $adms = Adm::all();

        return view('admin.adms', compact('adms'));
    }

 
    public function store(Request $request)
    {
        if (session('user_type') !== 'adm') {
            return redirect('/login')->with('error', 'Acesso negado.');
        }

        // Validação com a regra para Senha
        $request->validate([
            'Nome'  => 'required|string|max:255',
            'Email' => 'required|email|unique:adm,Email',
            'Senha' => 'required|string|min:6', // <-- Validação adicionada
        ], [
            'Email.unique' => 'Este e-mail já está cadastrado como Administrador.',
            'Senha.min'    => 'A senha deve ter no mínimo 6 caracteres.'
        ]);

        // Atribuição com Hash::make()
        $adm = new Adm();
        $adm->Nome = $request->Nome;
        $adm->Email = $request->Email;
        $adm->Senha = Hash::make($request->Senha); // <-- Criptografia da senha
        $adm->save();

        return redirect('/admin/gerenciar-adms')->with('msg', 'Novo Administrador cadastrado com sucesso!');
    }


    public function destroy($id)
    {
        if (session('user_type') !== 'adm') {
            return redirect('/login')->with('error', 'Acesso negado.');
        }

        Adm::findOrFail($id)->delete();

        return redirect('/admin/gerenciar-adms')->with('msg', 'Administrador removido.');
    }
}