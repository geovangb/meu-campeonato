<?php

namespace App\Http\Controllers;

use App\Models\Jogador;
use App\Models\Time;
use App\DTOs\JogadorDTO;
use App\Services\JogadorService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class JogadorController extends Controller
{
    /**
     * @var JogadorService
     */
    protected JogadorService $service;

    /**
     * @param JogadorService $service
     */
    public function __construct(JogadorService $service)
    {
        $this->service = $service;
    }

    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $jogadores = Jogador::with('time')->get();
        return view('jogadores.index', compact('jogadores'));
    }

    /**
     * @return Factory|View|Application
     */
    public function create(): Factory|View|Application
    {
        $times = Time::all();
        return view('jogadores.create', compact('times'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $dto = JogadorDTO::fromRequest($request);
        $this->service->criar($dto, $request->file('foto'));

        return redirect()->route('jogadores.index')
            ->with('success', 'Jogador criado com sucesso!');
    }

    /**
     * @param Jogador $jogador
     * @return Factory|View|Application
     */
    public function edit(Jogador $jogador): Factory|View|Application
    {
        $times = Time::all();
        return view('jogadores.edit', compact('jogador', 'times'));
    }

    /**
     * @param Request $request
     * @param Jogador $jogador
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Jogador $jogador)
    {
        $dto = JogadorDTO::fromRequest($request);
        $this->service->atualizar($jogador, $dto, $request->file('foto'));

        return redirect()->route('jogadores.index')
            ->with('success', 'Jogador atualizado com sucesso!');
    }

    /**
     * @param Jogador $jogador
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Jogador $jogador)
    {
        $this->service->remover($jogador);

        return redirect()->route('jogadores.index')
            ->with('success', 'Jogador removido com sucesso!');
    }
}
