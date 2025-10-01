<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campeonato;
use App\Services\CampeonatoStarterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
class CampeonatoStarterApiController extends Controller
{
    protected CampeonatoStarterService $service;

    /**
     * @param CampeonatoStarterService $service
     */
    public function __construct(CampeonatoStarterService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @param Campeonato $campeonato
     * @return JsonResponse
     */
    public function salvarTimes(Request $request, Campeonato $campeonato)
    {
        $this->service->salvarTimes($campeonato, $request->times);
        return response()->json(['success' => true]);
    }

    /**
     * @param Request $request
     * @param Campeonato $campeonato
     * @return JsonResponse
     */
    public function salvarRegras(Request $request, Campeonato $campeonato)
    {
        $campeonato = $this->service->salvarRegras($campeonato, $request->all());
        return response()->json(['success' => true, 'campeonato' => $campeonato]);
    }

    /**
     * @param Request $request
     * @param Campeonato $campeonato
     * @return JsonResponse
     */
    public function sortear(Request $request, Campeonato $campeonato)
    {
        try {
            $confrontos = $this->service->sortear($campeonato);
            return response()->json(['success' => true, 'confrontos' => $confrontos]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function status(Campeonato $campeonato)
    {
        $times = $campeonato->times()->get(['times.id', 'times.nome']);
        $regras = $campeonato->only(['penaltis', 'prorrogacao', 'criterio_desempate']);

        $jogos = $campeonato->jogos()
            ->with(['timeCasa:id,nome', 'timeFora:id,nome'])
            ->orderBy('id')
            ->get();

        // Agrupar ida/volta para montar confrontos
        $confrontos = [];
        foreach ($jogos->groupBy(function ($j) {
            return $j->time_casa_id . '-' . $j->time_fora_id;
        }) as $grupo) {
            $ida = $grupo->firstWhere('partida', 1);
            $volta = $grupo->firstWhere('partida', 2);

            $confrontos[] = [
                'time1'      => $ida?->timeCasa?->nome ?? '',
                'time2'      => $ida?->timeFora?->nome ?? '',
                'jogo_ida'   => $ida?->id,
                'jogo_volta' => $volta?->id,
                'data_ida'   => $ida?->data_partida,
                'data_volta' => $volta?->data_partida,
            ];
        }

        return response()->json([
            'success'    => true,
            'times'      => $times,
            'regras'     => $regras,
            'confrontos' => $confrontos,
        ]);
    }
}
