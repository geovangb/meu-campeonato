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

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Campeonato;
class CampeonatoSeeder  extends Seeder
{
    public function run(): void
    {
        $tipos = Campeonato::$mapaTimes;

        foreach ($tipos as $tipo => $qtd) {
            Campeonato::create([
                'nome' => "Campeonato de {$tipo}",
                'status' => 1,
                'data' => now(),
                'tipo_campeonato' => $tipo,
                'campeao' => null,
                'vice' => null,
                'terceiro_lugar' => null,
            ]);
        }
    }
}
