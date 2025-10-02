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

    public function __construct(CampeonatoStarterService $service)
    {
        $this->service = $service;
    }

    public function salvarTimes(Request $request, Campeonato $campeonato)
    {
        $this->service->salvarTimes($campeonato, $request->times);
        return response()->json(['success' => true]);
    }

    public function salvarRegras(Request $request, Campeonato $campeonato)
    {
        $campeonato = $this->service->salvarRegras($campeonato, $request->all());
        return response()->json(['success' => true, 'campeonato' => $campeonato]);
    }

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
        $data = $this->service->status($campeonato);

        return response()->json(['success' => true] + $data);
    }
}
