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

use App\DTOs\SalvarTimesDTO;
use App\DTOs\SalvarRegrasDTO;
use App\Models\Campeonato;
use App\Models\CampeonatoTime;
use App\Models\Jogo;
use App\Models\Time;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CampeonatoStarterService
{
    /**
     * @param Campeonato $campeonato
     * @param SalvarTimesDTO $dto
     * @return bool
     */
    public function salvarTimes(Campeonato $campeonato, SalvarTimesDTO $dto): bool
    {
        foreach ($dto->timesIds as $timeId) {
            CampeonatoTime::firstOrCreate([
                'campeonato_id' => $campeonato->id,
                'time_id'       => $timeId,
            ]);
        }
        return true;
    }

    /**
     * @param Campeonato $campeonato
     * @param SalvarRegrasDTO $dto
     * @return Campeonato
     */
    public function salvarRegras(Campeonato $campeonato, SalvarRegrasDTO $dto): Campeonato
    {
        $campeonato->update([
            'penaltis'           => $dto->penaltis,
            'prorrogacao'        => $dto->prorrogacao,
            'criterio_desempate' => $dto->criterioDesempate ? 1 : 0,
        ]);

        return $campeonato;
    }

    /**
     * @param Campeonato $campeonato
     * @return array
     * @throws \Random\RandomException
     * @throws \Throwable
     */
    public function sortear(Campeonato $campeonato): array
    {
        $times = $campeonato->times()->pluck('times.id')->toArray();

        if (count($times) < 2) {
            throw new \Exception('Não há times suficientes no campeonato.');
        }
        if ($campeonato->qtd_times && count($times) < $campeonato->qtd_times) {
            throw new \Exception("Selecione ao menos {$campeonato->qtd_times} times.");
        }
        if (count($times) % 2 !== 0) {
            throw new \Exception('Número de times precisa ser par para gerar confrontos.');
        }

        shuffle($times);

        $hoje = Carbon::today();
        $proximoDomingo = $hoje->isSunday() ? $hoje : $hoje->next(Carbon::SUNDAY);
        $intervalDays = 3;
        $confrontos   = [];

        DB::beginTransaction();
        try {
            $pairIndex = 0;

            for ($i = 0; $i < count($times); $i += 2) {
                $time1 = $times[$i];
                $time2 = $times[$i + 1];

                [$casa, $fora] = random_int(0, 1) ? [$time1, $time2] : [$time2, $time1];

                $dataIda   = $proximoDomingo->copy()->addDays($pairIndex * $intervalDays)->setTime(15, 0, 0);
                $dataVolta = $dataIda->copy()->addDays($intervalDays);

                $jogoIda = Jogo::create([
                    'campeonato_id' => $campeonato->id,
                    'time_casa_id'  => $casa,
                    'time_fora_id'  => $fora,
                    'partida'       => 1,
                    'data_partida'  => $dataIda,
                ]);

                $jogoVolta = Jogo::create([
                    'campeonato_id' => $campeonato->id,
                    'time_casa_id'  => $fora,
                    'time_fora_id'  => $casa,
                    'partida'       => 2,
                    'data_partida'  => $dataVolta,
                ]);

                $confrontos[] = [
                    'time1'      => Time::find($time1)->nome,
                    'time2'      => Time::find($time2)->nome,
                    'jogo_ida'   => $jogoIda->id,
                    'jogo_volta' => $jogoVolta->id,
                    'data_ida'   => $dataIda->toDateTimeString(),
                    'data_volta' => $dataVolta->toDateTimeString(),
                ];

                $pairIndex++;
            }

            DB::commit();
            return $confrontos;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param Campeonato $campeonato
     * @return array
     */
    public function status(Campeonato $campeonato): array
    {
        $times = $campeonato->times()->get(['times.id', 'times.nome']);
        $regras = $campeonato->only(['penaltis', 'prorrogacao', 'criterio_desempate']);

        $jogos = $campeonato->jogos()
            ->with(['timeCasa:id,nome', 'timeFora:id,nome'])
            ->orderBy('id')
            ->get();

        $confrontos = $this->montarConfrontos($jogos);

        return compact('times', 'regras', 'confrontos');
    }

    /**
     * @param $jogos
     * @return array
     */
    protected function montarConfrontos($jogos): array
    {
        $confrontos = [];
        foreach ($jogos->groupBy(fn($j) => $j->time_casa_id . '-' . $j->time_fora_id) as $grupo) {
            $ida = $grupo->firstWhere('partida', 1);
            $volta = $grupo->firstWhere('partida', 2);

            $confrontos[] = [
                'time1'      => $ida?->timeCasa?->nome ?? '',
                'time2'      => $ida?->timeFora?->nome ?? '',
                'jogo_ida'   => $ida?->id,
                'jogo_volta' => $volta?->id,
                'data_ida'   => $ida?->data_partida,
                'data_volta' => $volta?->data_partida,
            ];
        }
        return $confrontos;
    }
}
