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

namespace App\DTOs;

use Illuminate\Http\Request;

class BulkUpdateJogoDatesDTO
{
    /**
     * @param array<int, array{ id: int, data_partida: string|null }> $jogos
     */
    public function __construct(
        public array $jogos
    ) {}

    /**
     * Cria DTO diretamente de um Request validado.
     */
    public static function fromRequest(Request $request): self
    {
        $validated = $request->validate([
            'jogos' => 'required|array',
            'jogos.*.id' => 'required|integer|exists:jogos,id',
            'jogos.*.data_partida' => 'nullable|date',
        ]);

        return new self($validated['jogos']);
    }
}
