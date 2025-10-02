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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Jogo;
use App\Services\JogoService;
use App\DTOs\UpdateJogoDateDTO;
use App\DTOs\BulkUpdateJogoDatesDTO;

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
class JogoApiController extends Controller
{
    /**
     * @var JogoService
     */
    protected JogoService $service;

    /**
     * @param JogoService $service
     */
    public function __construct(JogoService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @param Jogo $jogo
     * @return JsonResponse
     */
    public function updateDate(Request $request, Jogo $jogo)
    {
        $dto = new UpdateJogoDateDTO($request->validate(['data_partida' => 'required|date']));
        $jogo = $this->service->updateDate($jogo, $dto);

        return response()->json(['success' => true, 'jogo' => $jogo]);
    }

    /**
     * @param Request $request
     * @param JogoService $service
     * @return JsonResponse
     * @throws \Throwable
     */
    public function bulkUpdateDates(Request $request, JogoService $service)
    {
        $dto = BulkUpdateJogoDatesDTO::fromRequest($request);

        $service->bulkUpdateDates($dto);

        return response()->json([
            'success' => true,
            'message' => 'Datas dos jogos atualizadas com sucesso!',
        ]);
    }

    /**
     * @param Request $request
     * @param Jogo $jogo
     * @return JsonResponse
     */
    public function update(Request $request, Jogo $jogo)
    {
        $dados = $request->only(['gols_casa', 'gols_fora']);
        $confrontos = $this->service->updateScores($jogo, $dados);

        return response()->json(['success' => true, 'confrontos_proxima_fase' => $confrontos]);
    }
}
