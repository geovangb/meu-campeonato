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

class GerarJogosDTO
{
    public int $campeonatoId;
    public string $fase;
    public ?int $intervalDays;

    public function __construct(array $data)
    {
        $this->campeonatoId = $data['campeonato_id'];
        $this->fase = $data['fase'] ?? 'quartas';
        $this->intervalDays = $data['interval_days'] ?? 3;
    }
}
