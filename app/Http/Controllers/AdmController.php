<?php

namespace App\Http\Controllers;

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

        $request->validate([
            'Nome' => 'required|string|max:255',
            'Email' => 'required|email|unique:adm,Email',
        ], [
            'Email.unique' => 'Este e-mail já está cadastrado como Administrador.'
        ]);

        $adm = new Adm();
        $adm->Nome = $request->Nome;
        $adm->Email = $request->Email;
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