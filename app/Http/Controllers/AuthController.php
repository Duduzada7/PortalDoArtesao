<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artesao;
use App\Models\Adm;

class AuthController extends Controller
{
    // Exibe a tela de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Processa o login (POST)
    public function login(Request $request)
    {
        $request->validate([
            'email'        => 'required|email',
            'tipo_usuario' => 'required|string',
        ]);

        $email = $request->input('email');
        $tipo  = $request->input('tipo_usuario'); // Captura exata de name="tipo_usuario"

        // -------------------------------------------------------------
        // FLUXO 1: LOGIN COMO ADMINISTRADOR
        // -------------------------------------------------------------
        if ($tipo === 'adm') {
            $adm = Adm::where('Email', $email)->first();

            if ($adm) {
                session([
                    'user_id'   => $adm->ID_ADM ?? $adm->id,
                    'user_name' => $adm->Nome ?? 'Administrador',
                    'user_type' => 'adm'
                ]);

                return redirect('/admin/dashboard');
            }

            return redirect()->back()->with('error', 'Credenciais de Administrador inválidas.');
        }

        // -------------------------------------------------------------
        // FLUXO 2: LOGIN COMO ARTESÃO
        // -------------------------------------------------------------
        if ($tipo === 'artesao') {
            $artesao = Artesao::where('Email', $email)->first();

            if (!$artesao) {
                return redirect()->back()->with('error', 'E-mail de Artesão não encontrado.');
            }

            // Checagens de Status
            if ($artesao->StatusAprovacao === 'pendente') {
                return redirect()->back()->with('error', 'Seu cadastro está em análise pela administração. Aguarde a aprovação para acessar.');
            }

            if ($artesao->StatusAprovacao === 'recusado') {
                return redirect()->back()->with('error', 'Seu cadastro foi recusado pela administração.');
            }

            // Login liberado
            session([
                'user_id'   => $artesao->ID_Artesao,
                'user_name' => $artesao->Nome,
                'user_type' => 'artesao'
            ]);

            return redirect('/artesao/dashboard');
        }

        return redirect()->back()->with('error', 'Selecione um tipo de acesso válido.');
    }

    public function logout()
    {
        session()->forget(['user_id', 'user_name', 'user_type']);
        return redirect('/login');
    }
}