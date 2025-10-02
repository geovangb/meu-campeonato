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

class UpdateJogoResultDTO
{
    public ?array $confrontosProximaFase;

    public function __construct(array $confrontos = null)
    {
        $this->confrontosProximaFase = $confrontos ?? [];
    }
}
