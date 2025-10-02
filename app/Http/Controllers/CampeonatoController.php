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
use App\Services\CampeonatoService;
use App\Services\JogoService;
use App\DTOs\CampeonatoDTO;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CampeonatoController extends Controller
{
    /**
     * @var CampeonatoService
     */
    protected CampeonatoService $service;
    /**
     * @var JogoService
     */
    protected JogoService $jogoService;

    /**
     * @param CampeonatoService $service
     * @param JogoService $jogoService
     */
    public function __construct(CampeonatoService $service, JogoService $jogoService)
    {
        $this->service = $service;
        $this->jogoService = $jogoService;
    }

    /**
     * @return Application|Factory|View
     */
    public function index(): Application|Factory|View
    {
        $campeonatos = Campeonato::latest()->paginate(10);
        return view('campeonatos.index', compact('campeonatos'));
    }

    /**
     * @return Application|Factory|View
     */
    public function create(): Application|Factory|View
    {
        return view('campeonatos.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $dto = CampeonatoDTO::fromRequest($request);
        $this->service->create($dto);

        return redirect()->route('campeonatos.index')
            ->with('success', 'Campeonato criado com sucesso!');
    }

    /**
     * @param Campeonato $campeonato
     * @return Application|Factory|View
     */
    public function edit(Campeonato $campeonato): Application|Factory|View
    {
        return view('campeonatos.edit', compact('campeonato'));
    }

    /**
     * @param Request $request
     * @param Campeonato $campeonato
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Campeonato $campeonato)
    {
        $dto = CampeonatoDTO::fromRequest($request);
        $this->service->update($campeonato, $dto);

        return redirect()->route('campeonatos.index')
            ->with('success', 'Campeonato atualizado com sucesso!');
    }

    /**
     * @param Campeonato $campeonato
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Campeonato $campeonato)
    {
        $this->service->delete($campeonato);

        return redirect()->route('campeonatos.index')
            ->with('success', 'Campeonato excluído com sucesso!');
    }

    /**
     * @param Campeonato $campeonato
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function iniciar(Campeonato $campeonato)
    {
        $primeiroJogo = $this->service->iniciarCampeonato($campeonato, $this->jogoService);

        return redirect()->route('jogos.edit', [$campeonato->id, $primeiroJogo->id]);
    }

    /**
     * @param Campeonato $campeonato
     * @return Application|Factory|View
     */
    public function jogos(Campeonato $campeonato): Application|Factory|View
    {
        $classificacao = $this->service->calcularClassificacao($campeonato);
        $jogos = $campeonato->jogos()->with(['timeCasa', 'timeFora'])->get();
        $top4 = $this->service->top4Ids($classificacao);

        return view('campeonatos.jogos', compact('campeonato','jogos','classificacao','top4'));
    }

    /**
     * @param Campeonato $campeonato
     * @return Application|Factory|View
     */
    public function jogosVisaoGeral(Campeonato $campeonato): Application|Factory|View
    {
        $classificacao = $this->service->calcularClassificacao($campeonato);
        $jogos = $campeonato->jogos()->with(['timeCasa','timeFora'])->get();
        $top4 = $this->service->top4Ids($classificacao);

        return view('campeonatos.jogos_geral', compact('campeonato','jogos','classificacao','top4'));
    }
}
