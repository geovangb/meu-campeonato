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

use Illuminate\Http\Request;
use App\Services\TimeService;
use App\DTOs\CreateTimeDTO;
use Throwable;

class TimeController extends Controller
{
    protected TimeService $service;

    public function __construct(TimeService $service)
    {
        $this->service = $service;
    }

    /**
     * Criar um novo time
     */
    public function store(Request $request)
    {
        try {
            $dto = CreateTimeDTO::fromRequest($request);

            $time = $this->service->create($dto);

            return response()->json([
                'success' => true,
                'message' => 'Time criado com sucesso.',
                'time' => $time
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar time: ' . $e->getMessage()
            ], 400);
        }
    }
}
