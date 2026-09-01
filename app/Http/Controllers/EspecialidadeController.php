<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Especialidades;

class EspecialidadeController extends Controller
{
    public function index()
    {
        $especialidades = Especialidades::orderBy('Nome', 'asc')->get();
        return view('admin.especialidades.index', compact('especialidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nome' => 'required|string|unique:especialidades,Nome|max:100',
        ], [
            'Nome.unique' => 'Esta especialidade já está cadastrada.'
        ]);

        $especialidade = Especialidades::create([
            'Nome' => trim($request->Nome)
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'especialidade' => $especialidade
            ]);
        }

        return redirect()->back()->with('msg', 'Especialidade cadastrada com sucesso!');
    }

    public function destroy($id)
    {
        $especialidade = Especialidades::findOrFail($id);
        $especialidade->delete();

        return redirect()->back()->with('msg', 'Especialidade removida!');
    }
}