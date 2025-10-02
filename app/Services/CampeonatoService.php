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
            $golsPro = $golsContra = $pontos = $cartoes = $jogados = 0;

            $jogosTime = $jogos->filter(fn($j) => $j->time_casa_id == $time->id || $j->time_fora_id == $time->id);

            foreach($jogosTime as $j) {
                $jogados++;

                if ($j->time_casa_id == $time->id) {
                    $golsPro     += $j->gols_casa ?? 0;
                    $golsContra  += $j->gols_fora ?? 0;

                    // pontos
                    if (!is_null($j->gols_casa) && !is_null($j->gols_fora)) {
                        if ($j->gols_casa > $j->gols_fora) $pontos += 3;
                        elseif ($j->gols_casa == $j->gols_fora) $pontos += 1;
                    }

                } else {
                    $golsPro     += $j->gols_fora ?? 0;
                    $golsContra  += $j->gols_casa ?? 0;

                    if (!is_null($j->gols_casa) && !is_null($j->gols_fora)) {
                        if ($j->gols_fora > $j->gols_casa) $pontos += 3;
                        elseif ($j->gols_fora == $j->gols_casa) $pontos += 1;
                    }
                }

                $cartoes += $j->sumula['cartoes'][$time->id] ?? 0;
            }

            return [
                'time'        => $time,
                'pontos'      => $pontos,
                'jogados'     => $jogados,
                'gols_pro'    => $golsPro,
                'gols_contra' => $golsContra,
                'saldo'       => $golsPro - $golsContra,
                'cartoes'     => $cartoes,
            ];
        })
            ->sortByDesc('pontos')
            ->sortByDesc('saldo')
            ->sortByDesc('gols_pro')
            ->values();
    }

    public function criarProximaFase(Campeonato $campeonato): void
    {
        $classificacao = $this->calcularClassificacao($campeonato);
        $top4 = $classificacao->take(4)->pluck('time');

        if ($top4->count() < 4) {
            throw new \Exception('Não há times suficientes para semifinais.');
        }

        // cria semifinal
        $partidas = [
            [$top4[0]->id, $top4[3]->id], // 1º x 4º
            [$top4[1]->id, $top4[2]->id], // 2º x 3º
        ];

        foreach ($partidas as [$casa, $fora]) {
            $campeonato->jogos()->create([
                'time_casa_id' => $casa,
                'time_fora_id' => $fora,
                'fase' => 'semifinal',
                'data_partida' => now()->addDays(2), // pode ser parametrizado
            ]);
        }
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
