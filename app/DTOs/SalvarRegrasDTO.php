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

class SalvarRegrasDTO extends DataTransferObject
{
    public ?bool $penaltis;
    public ?bool $prorrogacao;
    public ?string $criterio_desempate;

    public function __construct(array $data)
    {
        parent::__construct([
            'penaltis'           => $data['penaltis'] ?? null,
            'prorrogacao'        => $data['prorrogacao'] ?? null,
            'criterio_desempate' => $data['criterio_desempate'] ?? null,
        ]);
    }

    public static function fromRequest(Request $request): self
    {
        return new self($request->all());
    }
}

