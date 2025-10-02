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
class UpdateJogoDateDTO
{
    public int $id;
    public string $data_partida;

    public function __construct(array $data)
    {
        $this->id = (int) ($data['id'] ?? 0);
        $this->data_partida = $data['data_partida'] ?? '';
    }
}
