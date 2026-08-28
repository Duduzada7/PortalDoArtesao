<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artesao;
use App\Models\Evento;

class AprovacaoController extends Controller
{
    // Listagem geral de pendências (Artesãos e Candidaturas)
    public function index()
{
    // 1. Artesãos aguardando aprovação no sistema
    $artesaosPendentes = Artesao::where('StatusAprovacao', 'pendente')->get();

    // 2. Eventos que possuem candidaturas com status 'Inscrito'
    $eventosComCandidaturas = Evento::whereHas('artesaos', function($q) {
        $q->where('candidatura.StatusDaCandidatura', 'Inscrito');
    })->with(['artesaos' => function($q) {
        $q->where('candidatura.StatusDaCandidatura', 'Inscrito');
    }])->get();

    return view('admin.aprovacoes', compact('artesaosPendentes', 'eventosComCandidaturas'));
}

    // Aprovar Cadastro do Artesão no Sistema
    public function aprovarArtesao(Request $request, $id)
    {
        $artesao = Artesao::findOrFail($id);
        
        // Define a última posição da fila para o novo artesão
        $ultimaPosicao = Artesao::max('posicao_fila') ?? 0;

        $artesao->StatusAprovacao = 'aprovado';
        $artesao->posicao_fila = $ultimaPosicao + 1;
        $artesao->Aprovado_por = session('user_id'); // Registra o ADM que aprovou
        $artesao->save();

        return redirect()->back()->with('msg', 'Artesão aprovado e adicionado à fila com sucesso!');
    }

    // Recusar Cadastro do Artesão
    public function recusarArtesao($id)
    {
        $artesao = Artesao::findOrFail($id);
        $artesao->StatusAprovacao = 'recusado';
        $artesao->save();

        return redirect()->back()->with('msg', 'Cadastro de artesão recusado.');
    }

    // Aprovar Candidatura do Artesão em um Evento
    // Aprovar Candidatura do Artesão em um Evento
public function aprovarCandidatura($eventoId, $artesaoId)
{
    $evento = Evento::findOrFail($eventoId);
    
    $evento->artesaos()->updateExistingPivot($artesaoId, [
        'StatusDaCandidatura' => 'Aprovado'
    ]);

    // Redireciona mantendo a aba 'eventos' ativa
    return redirect('/admin/aprovacoes?tab=eventos')->with('msg', 'Candidatura aprovada para o evento!');
}

// Recusar Candidatura do Artesão em um Evento
public function recusarCandidatura($eventoId, $artesaoId)
{
    $evento = Evento::findOrFail($eventoId);
    
    $evento->artesaos()->updateExistingPivot($artesaoId, [
        'StatusDaCandidatura' => 'Recusado'
    ]);

    // Redireciona mantendo a aba 'eventos' ativa
    return redirect('/admin/aprovacoes?tab=eventos')->with('msg', 'Candidatura recusada.');
}
}