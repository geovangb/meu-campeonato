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

use Spatie\DataTransferObject\DataTransferObject;

class SalvarTimesDTO extends DataTransferObject
{
    public array $times;
}
