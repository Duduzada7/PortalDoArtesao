<?php

namespace App\Http\Controllers;

use App\Models\Artesao;
use Illuminate\Http\Request;

class AdminArtesaosController extends Controller
{
    public function index(Request $request)
    {
        if (session('user_type') !== 'adm') {
            return redirect('/login')->with('error', 'Acesso restrito a Administradores.');
        }

        $filtro = $request->query('status', 'todos');   // todos | ativos | pendentes
        $busca  = trim((string) $request->query('q', ''));

        // Recusados ficam fora da listagem (o Figma só tem Todos, Ativos e Pendentes)
        $visiveis = ['aprovado', 'pendente'];

        $contagens = [
            'todos'     => Artesao::whereIn('StatusAprovacao', $visiveis)->count(),
            'ativos'    => Artesao::where('StatusAprovacao', 'aprovado')->count(),
            'pendentes' => Artesao::where('StatusAprovacao', 'pendente')->count(),
        ];

        // Card amarelo do topo
        $pendentes = Artesao::with('especialidades')
            ->where('StatusAprovacao', 'pendente')
            ->orderBy('created_at')
            ->get();

        // Lista principal
        $artesaos = Artesao::with('especialidades')
            ->whereIn('StatusAprovacao', $visiveis)
            ->when($filtro === 'ativos', fn ($q) => $q->where('StatusAprovacao', 'aprovado'))
            ->when($filtro === 'pendentes', fn ($q) => $q->where('StatusAprovacao', 'pendente'))
            ->when($busca !== '', function ($q) use ($busca) {
                $q->where(function ($q) use ($busca) {
                    $q->where('Nome', 'like', "%{$busca}%")
                      ->orWhere('Email', 'like', "%{$busca}%")
                      ->orWhere('Bairro', 'like', "%{$busca}%");
                });
            })
            ->orderByRaw("StatusAprovacao = 'pendente' DESC")
            ->orderBy('Nome')
            ->get();

        return view('admin.artesaos', compact('artesaos', 'pendentes', 'contagens', 'filtro'));
    }
}