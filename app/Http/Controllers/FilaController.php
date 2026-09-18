<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artesao;

class FilaController extends Controller
{
    // Exibe a fila ordenada por prioridade
    public function index()
    {
        if (session('user_type') !== 'adm') {
            return redirect('/login')->with('error', 'Acesso negado.');
        }

        $artesaos = Artesao::where('StatusAprovacao', 'aprovado')
            ->orderBy('posicao_fila', 'asc')
            ->get();

        return view('admin.fila', compact('artesaos'));
    }

    // Move o artesão para o final da fila (ao ser selecionado em evento ou ação manual)
    public function moverParaFinal($id)
    {
        if (session('user_type') !== 'adm') {
            return redirect('/login')->with('error', 'Acesso negado.');
        }

        $artesao = Artesao::findOrFail($id);

        // Busca a maior posição atual entre os aprovados
        $maiorPosicao = Artesao::where('StatusAprovacao', 'aprovado')->max('posicao_fila') ?? 0;

        // Atualiza a posição e a data da última participação
        $artesao->posicao_fila = $maiorPosicao + 1;
        $artesao->DataUltimaParticipacao = now();
        $artesao->save();

        // Reordena sequencialmente todos os aprovados para não deixar "buracos" na numeração
        $todosArtesaos = Artesao::where('StatusAprovacao', 'aprovado')
            ->orderBy('posicao_fila', 'asc')
            ->get();

        foreach ($todosArtesaos as $index => $item) {
            $item->posicao_fila = $index + 1;
            $item->save();
        }

        return redirect('/admin/fila')->with('msg', 'Artesão reordenado para o final da fila!');
    }
}