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

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campeonato;
use App\Services\CampeonatoStarterService;
use Illuminate\Http\Request;
use App\DTOs\SalvarTimesDTO;
use App\DTOs\SalvarRegrasDTO;
use Throwable;

class CampeonatoStarterApiController extends Controller
{
    protected CampeonatoStarterService $service;

    public function __construct(CampeonatoStarterService $service)
    {
        $this->service = $service;
    }

    /**
     * Salvar times no campeonato
     */
    public function salvarTimes(Request $request, Campeonato $campeonato)
    {
        try {
            $dto = SalvarTimesDTO::fromRequest($request);
            $this->service->salvarTimes($campeonato, $dto);

            return response()->json([
                'success' => true,
                'message' => 'Times salvos com sucesso.'
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar times: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Salvar regras do campeonato
     */
    public function salvarRegras(Request $request, Campeonato $campeonato)
    {
        try {
            $dto = SalvarRegrasDTO::fromRequest($request);
            $campeonato = $this->service->salvarRegras($campeonato, $dto);

            return response()->json([
                'success' => true,
                'message' => 'Regras salvas com sucesso.',
                'campeonato' => $campeonato
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar regras: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Sortear confrontos
     */
    public function sortear(Campeonato $campeonato)
    {
        try {
            $confrontos = $this->service->sortear($campeonato);

            return response()->json([
                'success' => true,
                'message' => 'Sorteio realizado com sucesso.',
                'confrontos' => $confrontos
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao sortear: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Status do campeonato
     */
    public function status(Campeonato $campeonato)
    {
        try {
            $data = $this->service->status($campeonato);

            return response()->json([
                    'success' => true,
                    'message' => 'Status recuperado com sucesso.',
                ] + $data);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar status: ' . $e->getMessage()
            ], 400);
        }
    }
}
