<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adm;
use App\Models\Artesao;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'tipo_usuario' => 'required|in:adm,artesao'
        ]);

        if ($request->tipo_usuario === 'adm') {
            $user = Adm::where('Email', $request->email)->first();

            if ($user) {
                session([
                    'user_id' => $user->Id_ADM,
                    'user_nome' => $user->Nome,
                    'user_type' => 'adm'
                ]);
                return redirect('/admin/dashboard');
            }
        } else {
            $user = Artesao::where('Email', $request->email)->first();

            if ($user) {
                session([
                    'user_id' => $user->ID_Artesao,
                    'user_nome' => $user->Nome,
                    'user_type' => 'artesao'
                ]);
                return redirect('/artesao/dashboard');
            }
        }

        return back()->with('error', 'E-mail não encontrado para o tipo de perfil selecionado.');
    }

    public function logout()
    {
        session()->forget(['user_id', 'user_nome', 'user_type']);
        return redirect('/login');
    }
}
