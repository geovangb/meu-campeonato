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
use Illuminate\Support\Collection;
class ClassificacaoService
{
    /**
     * Calcula classificação completa de um campeonato
     */
    public function calcular(Campeonato $campeonato): Collection
    {
        $jogos = $campeonato->jogos()->with(['timeCasa','timeFora'])->get();

        return $campeonato->times()->get()->map(function($time) use ($jogos) {
            $pontos = 0;
            $golsPro = 0;
            $golsContra = 0;
            $cartoes = 0;

            $jogosTime = $jogos->filter(fn($j) => $j->time_casa_id == $time->id || $j->time_fora_id == $time->id);

            foreach ($jogosTime as $j) {
                if ($j->time_casa_id == $time->id) {
                    $golsPro += $j->gols_casa ?? 0;
                    $golsContra += $j->gols_fora ?? 0;
                    $pontos += ($j->gols_casa ?? 0) - ($j->gols_fora ?? 0);
                } else {
                    $golsPro += $j->gols_fora ?? 0;
                    $golsContra += $j->gols_casa ?? 0;
                    $pontos += ($j->gols_fora ?? 0) - ($j->gols_casa ?? 0);
                }

                $cartoes += $j->sumula['cartoes'][$time->id] ?? 0;
            }

            $time->pontos = $pontos;
            $time->gols_pro = $golsPro;
            $time->gols_contra = $golsContra;
            $time->cartoes = $cartoes;

            return $time;
        })->sortByDesc('pontos');
    }

    /**
     * Retorna ids dos top N times
     */
    public function topNIds(Collection $times, int $n = 4): array
    {
        return $times->take($n)->pluck('id')->toArray();
    }
}
