<?php

namespace App\Http\Controllers;

use App\Models\Artesao;
use App\Models\Especialidades;
use App\Models\Evento;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        $busca = trim((string) $request->query('q', ''));

        $artesaos = Artesao::query()
            ->with('especialidades')
            ->where('StatusAprovacao', 'aprovado') // traz apenas aprovados para o público
            ->when($busca !== '', function ($q) use ($busca) {
                $q->where(function ($q) use ($busca) {
                    $q->where('Nome', 'like', "%{$busca}%")
                      ->orWhere('Bairro', 'like', "%{$busca}%")
                      ->orWhereHas('especialidades', fn ($e) => $e->where('Nome', 'like', "%{$busca}%"));
                });
            })
            ->when($request->query('bairro'), fn ($q, $bairro) => $q->where('Bairro', $bairro))
            ->when($request->query('especialidade'), function ($q, $id) {
                $q->whereHas('especialidades', fn ($e) => $e->where('especialidades.ID_Especialidade', $id));
            })
            ->orderBy('Nome')
            ->get();

        // Traz eventos que NÃO estão marcados como encerrados
        $eventosAbertos = Evento::where(function($q) {
            $q->where('status', '!=', 'encerrado')
              ->orWhereNull('status');
        });

        $eventos = (clone $eventosAbertos)
            ->orderBy('Dia', 'asc')
            ->take(5)
            ->get();

        $bairros = Artesao::whereNotNull('Bairro')->distinct()->orderBy('Bairro')->pluck('Bairro');
        $especialidades = Especialidades::orderBy('Nome')->get();

        return view('welcome', [
            'artesaos'       => $artesaos,
            'eventos'        => $eventos,
            'especialidades' => $especialidades,
            'bairros'        => $bairros,
            'stats' => [
                'artesaos'       => Artesao::where('StatusAprovacao', 'aprovado')->count(),
                'especialidades' => $especialidades->count(),
                'eventos'        => (clone $eventosAbertos)->count(),
                'bairros'        => $bairros->count(),
            ],
        ]);
    }
}