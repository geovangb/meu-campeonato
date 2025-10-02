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
use Spatie\DataTransferObject\DataTransferObject;

class CampeonatoDTO extends DataTransferObject
{
    public string $nome;
    public bool $status;
    public ?string $data;
    public ?int $qtd_times;
    public ?string $tipo_campeonato;

    /**
     * Cria um DTO a partir de um Request
     */
    public static function fromRequest(Request $request): self
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:200',
            'status' => 'boolean',
            'data' => 'nullable|date',
            'qtd_times' => 'nullable|integer|min:0',
            'tipo_campeonato' => 'nullable|string|max:100',
        ]);

        return new self($validated);
    }
}
