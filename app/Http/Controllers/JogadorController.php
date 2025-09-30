<?php

namespace App\Http\Controllers;

use App\Models\Jogador;
use App\Models\Time;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JogadorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $jogadores = Jogador::with('time')->get();

        return view('jogadores.index', compact('jogadores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $times = Time::all();

        return view('jogadores.create', compact('times'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'time_id' => 'required|exists:times,id',
            'nome' => 'required|string|max:255',
            'nascimento' => 'nullable|date',
            'altura' => 'nullable|numeric',
            'peso' => 'nullable|numeric',
            'posicao' => 'nullable|string|max:50',
            'apto' => 'nullable|boolean',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('jogadores', 'public');
        }

        Jogador::create($data);

        return redirect()->route('jogadores.index')->with('success', 'Jogador criado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Jogador $jogador
     * @return Application|Factory|View
     */
    public function edit(Jogador $jogador)
    {
        $times = Time::all();
        return view('jogadores.edit', compact('jogador', 'times'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Jogador $jogador
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Jogador $jogador)
    {
        $data = $request->validate([
            'time_id' => 'required|exists:times,id',
            'nome' => 'required|string|max:255',
            'nascimento' => 'nullable|date',
            'altura' => 'nullable|numeric',
            'peso' => 'nullable|numeric',
            'posicao' => 'nullable|string|max:50',
            'apto' => 'nullable|boolean',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($jogador->foto) {
                Storage::disk('public')->delete($jogador->foto);
            }
            $data['foto'] = $request->file('foto')->store('jogadores', 'public');
        }

        $jogador->update($data);
        return redirect()->route('jogadores.index')->with('success', 'Jogador atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Jogador $jogador
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Jogador $jogador)
    {
        if ($jogador->foto) {
            Storage::disk('public')->delete($jogador->foto);
        }
        $jogador->delete();
        return redirect()->route('jogadores.index')->with('success', 'Jogador removido!');
    }
}
