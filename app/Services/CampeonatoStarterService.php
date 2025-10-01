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
use App\Models\CampeonatoTime;
use App\Models\Jogo;
use App\Models\Time;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class CampeonatoStarterService
{
    public function salvarTimes(Campeonato $campeonato, array $timesIds)
    {
        foreach ($timesIds as $timeId) {
            CampeonatoTime::firstOrCreate([
                'campeonato_id' => $campeonato->id,
                'time_id'       => $timeId,
            ]);
        }

        return true;
    }

    public function salvarRegras(Campeonato $campeonato, array $dados)
    {
        $campeonato->update([
            'penaltis'           => $dados['penaltis'],
            'prorrogacao'        => $dados['prorrogacao'],
            'criterio_desempate' => $dados['criterio_desempate'] === 'sim' ? 1 : 0,
        ]);

        return $campeonato;
    }

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
                    'time1'     => Time::find($time1)->nome,
                    'time2'     => Time::find($time2)->nome,
                    'jogo_ida'  => $jogoIda->id,
                    'jogo_volta'=> $jogoVolta->id,
                    'data_ida'  => $dataIda->toDateTimeString(),
                    'data_volta'=> $dataVolta->toDateTimeString(),
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
}
