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

class JogoDTO
{
    public function __construct(
        public int $campeonato_id,
        public int $time_casa_id,
        public int $time_fora_id,
        public int $partida,
        public ?string $data_partida = null,
        public ?string $juiz = null,
        public ?string $auxiliar_1 = null,
        public ?string $auxiliar_2 = null,
        public ?int $gols_casa = 0,
        public ?int $gols_fora = 0,
        public array $escalacao_time_1 = [],
        public array $reservas_time_1 = [],
        public array $substituicao_time_1 = [],
        public array $escalacao_time_2 = [],
        public array $reservas_time_2 = [],
        public array $substituicao_time_2 = [],
        public array $sumula = [],
    ) {}

    /**
     * Cria DTO diretamente de um Request validado.
     */
    public static function fromRequest(Request $request): self
    {
        $validated = $request->validate([
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

        return new self(
            campeonato_id: (int) $validated['campeonato_id'],
            time_casa_id: (int) $validated['time_casa_id'],
            time_fora_id: (int) $validated['time_fora_id'],
            partida: (int) $validated['partida'],
            data_partida: $validated['data_partida'] ?? null,
            juiz: $validated['juiz'] ?? null,
            auxiliar_1: $validated['auxiliar_1'] ?? null,
            auxiliar_2: $validated['auxiliar_2'] ?? null,
            gols_casa: isset($validated['gols_casa']) ? (int) $validated['gols_casa'] : 0,
            gols_fora: isset($validated['gols_fora']) ? (int) $validated['gols_fora'] : 0,
            escalacao_time_1: $validated['escalacao_time_1'] ?? [],
            reservas_time_1: $validated['reservas_time_1'] ?? [],
            substituicao_time_1: $validated['substituicao_time_1'] ?? [],
            escalacao_time_2: $validated['escalacao_time_2'] ?? [],
            reservas_time_2: $validated['reservas_time_2'] ?? [],
            substituicao_time_2: $validated['substituicao_time_2'] ?? [],
            sumula: $validated['sumula'] ?? [],
        );
    }
}
