<?php

namespace App\Http\Controllers;

use App\DTOs\JogoDTO;
use App\Models\Jogo;
use App\Models\Time;
use App\Models\Campeonato;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Services\JogoService;

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
class JogoController extends Controller
{

    protected JogoService $service;

    public function __construct(JogoService $service)
    {
        $this->service = $service;
    }

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
    public function store(Request $request, JogoService $service)
    {
        $dto = JogoDTO::fromRequest($request);
        $service->create($dto);

        return redirect()->route('jogos.index')
            ->with('success', 'Jogo criado com sucesso!');
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
        $dto = JogoDTO::fromRequest($request);
        $service->update($jogo, $dto);

        if ($request->wantsJson() || $request->header('Accept') === 'application/json') {
            return response()->json([
                'success' => true,
                'jogo' => $jogo->fresh()
            ]);
        }

        return redirect()->route('jogos.edit', [$campeonatoId, $jogo->id])
            ->with('success', 'Jogo atualizado com sucesso!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Jogo $jogo
     * @param JogoService $service
     * @return RedirectResponse
     */
    public function destroy(Jogo $jogo, JogoService $service)
    {
        $service->delete($jogo);

        return redirect()->route('jogos.index')
            ->with('success', 'Jogo excluído com sucesso!');
    }
}
