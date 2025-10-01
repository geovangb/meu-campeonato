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

use App\Models\Campeonato;
use App\Models\Jogo;
use Carbon\Carbon;
class JogoService
{
    public function gerarSemifinal(Campeonato $campeonato)
    {
        $quartas = $campeonato->jogos()->where('fase', 'quartas')->get();

        $vencedores = $quartas->map(function($jogo) {
            $saldo = ($jogo->gols_casa ?? 0) - ($jogo->gols_fora ?? 0);

            if($saldo > 0) return $jogo->time_casa_id;
            if($saldo < 0) return $jogo->time_fora_id;

            return random_int(0,1) ? $jogo->time_casa_id : $jogo->time_fora_id;
        })->values();

        if(count($vencedores) != 4) {
            throw new \Exception("Não é possível gerar semifinal, número de vencedores inválido.");
        }

        $confrontos = [
            [$vencedores[0], $vencedores[3]],
            [$vencedores[1], $vencedores[2]],
        ];

        $hoje = Carbon::today();
        $proximoDomingo = $hoje->copy()->next(Carbon::SUNDAY);
        $interval = 3;
        $pairIndex = 0;

        foreach($confrontos as $c) {
            $dataIda = $proximoDomingo->copy()->addDays($pairIndex * $interval)->setTime(15,0,0);
            $dataVolta = $dataIda->copy()->addDays($interval);

            Jogo::create([
                'campeonato_id' => $campeonato->id,
                'time_casa_id' => $c[0],
                'time_fora_id' => $c[1],
                'partida' => 1,
                'fase' => 'semifinal',
                'data_partida' => $dataIda,
            ]);

            Jogo::create([
                'campeonato_id' => $campeonato->id,
                'time_casa_id' => $c[1],
                'time_fora_id' => $c[0],
                'partida' => 2,
                'fase' => 'semifinal',
                'data_partida' => $dataVolta,
            ]);

            $pairIndex++;
        }
    }
}
