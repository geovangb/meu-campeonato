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

namespace App\Enums;

enum TipoCampeonato: string
{
    case FASE_DE_GRUPOS = 'Fase de Grupos (32 times)';
    case OITAVAS = 'Oitavas de Final (16 times)';
    case QUARTAS = 'Copa Quartas de final (8 times)';
    case SEMI = 'Semi Final (4 times)';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
