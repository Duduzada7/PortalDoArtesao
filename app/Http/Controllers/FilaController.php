<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artesao;

class FilaController extends Controller
{
    // Exibe a fila ordenada por prioridade
    public function index()
    {
        $artesaos = Artesao::where('StatusAprovacao', 'aprovado')
            ->orderBy('posicao_fila', 'asc')
            ->get();

        return view('admin.fila', compact('artesaos'));
    }

    // Move o artesão para o final da fila (ao ser selecionado em evento)
    public function moverParaFinal($id)
    {
        $artesao = Artesao::findOrFail($id);

        // Busca o maior número de posição atual no banco
        $maiorPosicao = Artesao::where('StatusAprovacao', 'aprovado')->max('posicao_fila') ?? 0;

        // Atribui uma posição maior para jogar o artesão para o fim
        $artesao->posicao_fila = $maiorPosicao + 1;
        $artesao->save();

        return redirect('/admin/fila')->with('msg', 'Artesão reordenado para o final da fila!');
    }
}