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

class JogadorDTO extends DataTransferObject
{
    public int $time_id;
    public string $nome;
    public ?string $nascimento;
    public ?float $altura;
    public ?float $peso;
    public ?string $posicao;
    public ?bool $apto;

    /**
     * Cria um DTO a partir de um Request
     */
    public static function fromRequest(Request $request): self
    {
        $validated = $request->validate([
            'time_id' => 'required|exists:times,id',
            'nome' => 'required|string|max:255',
            'nascimento' => 'nullable|date',
            'altura' => 'nullable|numeric',
            'peso' => 'nullable|numeric',
            'posicao' => 'nullable|string|max:50',
            'apto' => 'nullable|boolean',
        ]);

        return new self($validated);
    }
}

