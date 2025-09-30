<?php
/**
 * GB Developer
 *
 * @category GB_Developer
 * @package  GB
 *
 * @copyright Copyright (c) 2025 GB Developer.
 *
 * @author Geovan Brambilla <geovangb@gmail.com>
 */

namespace App\Http\Controllers;

use App\Models\Campeonato;
use Illuminate\Http\Request;
class CampeonatoController
{
    public function index()
    {
        $campeonatos = Campeonato::latest()->paginate(10);
        return view('campeonatos.index', compact('campeonatos'));
    }

    public function create()
    {
        return view('campeonatos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:200',
            'status' => 'boolean',
            'data' => 'nullable|date',
            'qtd_times' => 'nullable|integer|min:0',
            'tipo_campeonato' => 'nullable|string|max:100',
        ]);

        Campeonato::create($validated);

        return redirect()->route('campeonatos.index')->with('success', 'Campeonato criado com sucesso!');
    }

    public function edit(Campeonato $campeonato)
    {
        return view('campeonatos.edit', compact('campeonato'));
    }

    public function update(Request $request, Campeonato $campeonato)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:200',
            'status' => 'boolean',
            'data' => 'nullable|date',
            'qtd_times' => 'nullable|integer|min:0',
            'tipo_campeonato' => 'nullable|string|max:100',
        ]);

        $campeonato->update($validated);

        return redirect()->route('campeonatos.index')->with('success', 'Campeonato atualizado com sucesso!');
    }

    public function destroy(Campeonato $campeonato)
    {
        $campeonato->delete();
        return redirect()->route('campeonatos.index')->with('success', 'Campeonato excluído com sucesso!');
    }

    public function starter(Campeonato $campeonato)
    {

    }
}
