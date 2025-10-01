<?php

namespace App\Http\Controllers;

use App\Models\Jogo;
use App\Models\Time;
use App\Models\Campeonato;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Services\JogoService;

class JogoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $jogos = Jogo::with(['timeCasa', 'timeFora', 'campeonato'])->get();

        return view('jogos.index', compact('jogos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $times = Time::all();
        $campeonatos = Campeonato::all();

        return view('jogos.create', compact('times', 'campeonatos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'campeonato_id'   => 'required|integer',
            'time_casa_id'    => 'required|integer',
            'time_fora_id'    => 'required|integer|different:time_casa_id',
            'partida'         => 'required|integer',
            'data_partida'    => 'nullable|date',
            'juiz'            => 'nullable|string',
            'auxiliar_1'      => 'nullable|string',
            'auxiliar_2'      => 'nullable|string',
            'gols_casa'       => 'nullable|integer',
            'gols_fora'       => 'nullable|integer',
            'escalacao_time_1'=> 'nullable|array',
            'reservas_time_1' => 'nullable|array',
            'substituicao_time_1' => 'nullable|array',
            'escalacao_time_2'=> 'nullable|array',
            'reservas_time_2' => 'nullable|array',
            'substituicao_time_2' => 'nullable|array',
            'sumula'          => 'nullable|array',
        ]);

        Jogo::create($data);

        return redirect()->route('jogos.index')->with('success', 'Jogo criado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param Jogo $jogo
     * @return Application|Factory|View
     */
    public function show(Jogo $jogo)
    {
        return view('jogos.show', compact('jogo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Jogo $jogo
     * @return Application|Factory|View
     */
    public function edit($campeonato, $jogo)
    {
        $jogo = Jogo::with([
            'timeCasa.jogadores',
            'timeFora.jogadores'
        ])->findOrFail($jogo);

        $times = Time::all();
        $campeonatos = Campeonato::all();

        return view('jogos.edit', compact('jogo', 'times', 'campeonatos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $campeonatoId
     * @param Jogo $jogo
     * @param JogoService $service
     * @return RedirectResponse
     * @throws \Exception
     */
    public function update(Request $request, $campeonatoId, Jogo $jogo, JogoService $service)
    {
        $data = $request->validate([
            'campeonato_id'   => 'required|integer',
            'time_casa_id'    => 'required|integer',
            'time_fora_id'    => 'required|integer|different:time_casa_id',
            'partida'         => 'required|integer',
            'data_partida'    => 'nullable|date',
            'juiz'            => 'nullable|string',
            'auxiliar_1'      => 'nullable|string',
            'auxiliar_2'      => 'nullable|string',
            'gols_casa'       => 'nullable|integer',
            'gols_fora'       => 'nullable|integer',
            'escalacao_time_1'=> 'nullable|array',
            'reservas_time_1' => 'nullable|array',
            'substituicao_time_1' => 'nullable|array',
            'escalacao_time_2'=> 'nullable|array',
            'reservas_time_2' => 'nullable|array',
            'substituicao_time_2' => 'nullable|array',
            'sumula'          => 'nullable|array',
        ]);

        $jogo->update($data);

        $jogo->timeCasa->increment('pontos', ($jogo->gols_casa ?? 0) - ($jogo->gols_fora ?? 0));
        $jogo->timeFora->increment('pontos', ($jogo->gols_fora ?? 0) - ($jogo->gols_casa ?? 0));

        $campeonato = $jogo->campeonato;
        $faseAnterior = 'quartas';
        $jogosFaseAnterior = $campeonato->jogos()->where('fase', $faseAnterior)->get();

        if($jogosFaseAnterior->every(fn($j) => $j->gols_casa !== null && $j->gols_fora !== null)) {
            $service->gerarSemifinal($campeonato);
        }

        return redirect()->route('jogos.edit', [$campeonatoId, $jogo->id])
            ->with('success', 'Jogo atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Jogo $jogo
     * @return RedirectResponse
     */
    public function destroy(Jogo $jogo)
    {
        $jogo->delete();
        return redirect()->route('jogos.index')->with('success', 'Jogo excluído com sucesso!');
    }
}
