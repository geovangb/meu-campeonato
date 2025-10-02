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
use App\DTOs\CampeonatoDTO;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CampeonatoService
{
    /**
     * @param CampeonatoDTO $dto
     * @return Campeonato
     */
    public function create(CampeonatoDTO $dto): Campeonato
    {
        return Campeonato::create($dto->toArray());
    }

    /**
     * @param Campeonato $campeonato
     * @param CampeonatoDTO $dto
     * @return Campeonato
     */
    public function update(Campeonato $campeonato, CampeonatoDTO $dto): Campeonato
    {
        $campeonato->update($dto->toArray());
        return $campeonato;
    }

    /**
     * @param Campeonato $campeonato
     * @param JogoService $jogoService
     * @return Model|HasMany|mixed|object|null
     * @throws \Exception
     */
    public function iniciarCampeonato(Campeonato $campeonato, JogoService $jogoService)
    {
        if ($campeonato->jogos()->count() === 0) {
            $jogosQuartas = $jogoService->gerarJogosQuartas($campeonato);
            return $jogosQuartas->first();
        }

        return $campeonato->jogos()->first();
    }

    /**
     * @param Campeonato $campeonato
     * @return Collection|\Illuminate\Support\Collection
     */
    public function calcularClassificacao(Campeonato $campeonato)
    {
        $jogos = $campeonato->jogos()->with(['timeCasa','timeFora'])->get();

        return $campeonato->times()->get()->map(function($time) use ($jogos) {
            $golsPro = $golsContra = $pontos = $cartoes = 0;

            $jogosTime = $jogos->filter(fn($j) => $j->time_casa_id == $time->id || $j->time_fora_id == $time->id);

            foreach($jogosTime as $j) {
                if ($j->time_casa_id == $time->id) {
                    $golsPro += $j->gols_casa ?? 0;
                    $golsContra += $j->gols_fora ?? 0;
                } else {
                    $golsPro += $j->gols_fora ?? 0;
                    $golsContra += $j->gols_casa ?? 0;
                }

                $cartoes += $j->sumula['cartoes'][$time->id] ?? 0;
            }

            $pontos = $golsPro - $golsContra;

            return [
                'time' => $time,
                'pontos' => $pontos,
                'gols_pro' => $golsPro,
                'gols_contra' => $golsContra,
                'cartoes' => $cartoes,
            ];
        })->sortByDesc('pontos')->values();
    }

    /**
     * @param $times
     * @return mixed
     */
    public function top4Ids($times)
    {
        return $times->take(4)->pluck('time.id')->toArray();
    }
}
