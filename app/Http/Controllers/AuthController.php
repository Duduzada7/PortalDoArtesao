<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Artesao;
use App\Models\Adm;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'Email' => 'required|email',
            'Senha' => 'required|string',
        ]);

        $email = $request->input('Email');
        $senha = $request->input('Senha');

        $adm = Adm::where('Email', $email)->first();

        if ($adm && Hash::check($senha, $adm->Senha)) {
            session([
                'user_id'   => $adm->ID_ADM ?? $adm->id,
                'user_name' => $adm->Nome ?? 'Administrador',
                'user_type' => 'adm'
            ]);

            return redirect('/admin/dashboard');
        }

  
        $artesao = Artesao::where('Email', $email)->first();

        if ($artesao && Hash::check($senha, $artesao->Senha)) {

     
            if ($artesao->StatusAprovacao === 'pendente') {
                return redirect()->back()->with('error', 'Seu cadastro está em análise pela administração. Aguarde a aprovação para acessar.');
            }

            if ($artesao->StatusAprovacao === 'recusado') {
                return redirect()->back()->with('error', 'Seu cadastro foi recusado pela administração.');
            }

         
            session([
                'user_id'   => $artesao->ID_Artesao ?? $artesao->id,
                'user_name' => $artesao->Nome,
                'user_type' => 'artesao'
            ]);

            return redirect('/artesao/dashboard');
        }

       
        return redirect()->back()->with('error', 'E-mail ou senha incorretos.');
    }

    public function logout()
    {
        session()->forget(['user_id', 'user_name', 'user_type']);
        return redirect('/login')->with('msg', 'Sessão encerrada com sucesso.');
    }
}