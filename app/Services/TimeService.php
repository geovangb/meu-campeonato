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

namespace App\Services;

use App\DTOs\CreateTimeDTO;
use App\Models\Time;
class TimeService
{
    public function create(CreateTimeDTO $dto): Time
    {
        return Time::create([
            'nome' => $dto->nome
        ]);
    }
}
