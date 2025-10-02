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

use App\DTOs\BulkUpdateJogoDatesDTO;
use App\Models\Jogo;
use App\Models\Campeonato;
use App\DTOs\JogoDTO;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JogoService
{
    /**
     * Cria um jogo a partir de um DTO
     */
    public function create(JogoDTO $dto): Jogo
    {
        return Jogo::create($dto->toArray());
    }

    /**
     * Atualiza um jogo e trata lógica de pontos e próxima fase
     */
    public function updateJogo(Jogo $jogo, JogoDTO $dto): Jogo
    {
        // Atualiza os dados do jogo
        $jogo->update($dto->toArray());

        // Atualiza pontos dos times
        if ($jogo->gols_casa !== null && $jogo->gols_fora !== null) {
            $jogo->timeCasa->increment('pontos', ($jogo->gols_casa ?? 0) - ($jogo->gols_fora ?? 0));
            $jogo->timeFora->increment('pontos', ($jogo->gols_fora ?? 0) - ($jogo->gols_casa ?? 0));
        }

        $campeonato = $jogo->campeonato;
        $faseAnterior = match ($jogo->fase) {
            'quartas' => 'quartas',
            'semifinal' => 'semifinal',
            default => null,
        };

        if ($faseAnterior) {
            $jogosFaseAnterior = $campeonato->jogos()->where('fase', $faseAnterior)->get();

            if ($jogosFaseAnterior->every(fn($j) => $j->gols_casa !== null && $j->gols_fora !== null)) {
                if ($faseAnterior === 'quartas') {
                    $this->gerarSemifinal($campeonato);
                }
            }
        }

        return $jogo;
    }

    /**
     * Remove um jogo
     */
    public function delete(Jogo $jogo): void
    {
        $jogo->delete();
    }

    /**
     * Gera jogos de quartas de final
     */
    public function gerarJogosQuartas(Campeonato $campeonato)
    {
        $times = $campeonato->times()->pluck('id')->toArray();
        if (count($times) < 4) {
            throw new \Exception("Não há times suficientes para gerar quartas de final.");
        }

        shuffle($times);
        $confrontos = [
            [$times[0], $times[3]],
            [$times[1], $times[2]],
        ];

        $hoje = Carbon::today();
        $proximoDomingo = $hoje->isSunday() ? $hoje : $hoje->next(Carbon::SUNDAY);
        $interval = 3;

        $jogosCriados = collect();
        $pairIndex = 0;

        foreach ($confrontos as $c) {
            $dataIda = $proximoDomingo->copy()->addDays($pairIndex * $interval)->setTime(15,0,0);
            $dataVolta = $dataIda->copy()->addDays($interval);

            $jogosCriados->push(
                Jogo::create([
                    'campeonato_id' => $campeonato->id,
                    'time_casa_id' => $c[0],
                    'time_fora_id' => $c[1],
                    'partida' => 1,
                    'fase' => 'quartas',
                    'data_partida' => $dataIda,
                ]),
                Jogo::create([
                    'campeonato_id' => $campeonato->id,
                    'time_casa_id' => $c[1],
                    'time_fora_id' => $c[0],
                    'partida' => 2,
                    'fase' => 'quartas',
                    'data_partida' => $dataVolta,
                ])
            );

            $pairIndex++;
        }

        return $jogosCriados;
    }

    /**
     * Gera semifinal a partir dos vencedores das quartas
     */
    public function gerarSemifinal(Campeonato $campeonato)
    {
        $quartas = $campeonato->jogos()->where('fase', 'quartas')->get();

        $vencedores = $quartas->map(function($jogo) {
            $saldo = ($jogo->gols_casa ?? 0) - ($jogo->gols_fora ?? 0);
            if ($saldo > 0) return $jogo->time_casa_id;
            if ($saldo < 0) return $jogo->time_fora_id;
            return random_int(0,1) ? $jogo->time_casa_id : $jogo->time_fora_id;
        })->values();

        if (count($vencedores) !== 4) {
            throw new \Exception("Não é possível gerar semifinal, número de vencedores inválido.");
        }

        $confrontos = [
            [$vencedores[0], $vencedores[3]],
            [$vencedores[1], $vencedores[2]],
        ];

        $hoje = Carbon::today();
        $proximoDomingo = $hoje->isSunday() ? $hoje : $hoje->next(Carbon::SUNDAY);
        $interval = 3;

        $pairIndex = 0;
        foreach ($confrontos as $c) {
            $dataIda = $proximoDomingo->copy()->addDays($pairIndex * $interval)->setTime(15,0,0);
            $dataVolta = $dataIda->copy()->addDays($interval);

            Jogo::create([
                'campeonato_id' => $campeonato->id,
                'time_casa_id'  => $c[0],
                'time_fora_id'  => $c[1],
                'partida'       => 1,
                'fase'          => 'semifinal',
                'data_partida'  => $dataIda,
            ]);

            Jogo::create([
                'campeonato_id' => $campeonato->id,
                'time_casa_id'  => $c[1],
                'time_fora_id'  => $c[0],
                'partida'       => 2,
                'fase'          => 'semifinal',
                'data_partida'  => $dataVolta,
            ]);

            $pairIndex++;
        }
    }

    /**
     * Atualiza em massa as datas dos jogos.
     *
     * @param BulkUpdateJogoDatesDTO $dto
     * @return array
     * @throws \Throwable
     */
    public function bulkUpdateDates(BulkUpdateJogoDatesDTO $dto): array
    {
        $resultados = [];

        DB::transaction(function () use ($dto, &$resultados) {
            foreach ($dto->jogos as $jogoData) {
                $jogo = Jogo::find($jogoData['id']);

                if (! $jogo) {
                    $resultados[] = [
                        'id' => $jogoData['id'],
                        'status' => 'error',
                        'message' => "Jogo ID {$jogoData['id']} não encontrado."
                    ];
                    continue;
                }

                try {
                    $jogo->update([
                        'data_partida' => $jogoData['data_partida']
                    ]);

                    $resultados[] = [
                        'id' => $jogo->id,
                        'status' => 'success',
                        'message' => "Data do jogo {$jogo->id} atualizada."
                    ];
                } catch (Exception $e) {
                    $resultados[] = [
                        'id' => $jogoData['id'],
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ];
                }
            }
        });

        return $resultados;
    }

    /**
     * @param Jogo $jogo
     * @param JogoDTO $dto
     * @return Jogo
     * @throws \Exception
     */
    public function update(Jogo $jogo, JogoDTO $dto): Jogo
    {
        $jogo->update((array) $dto);

        $saldoCasa = ($jogo->gols_casa ?? 0) - ($jogo->gols_fora ?? 0);
        $saldoFora = ($jogo->gols_fora ?? 0) - ($jogo->gols_casa ?? 0);

        $jogo->saldo_casa = $saldoCasa;
        $jogo->saldo_fora = $saldoFora;

        $faseAtual = $jogo->fase ?? null;

        if ($faseAtual) {
            $campeonato = $jogo->campeonato;
            $faseAnterior = 'quartas';
            $jogosFaseAnterior = $campeonato->jogos()->where('fase', $faseAnterior)->get();

            if ($jogosFaseAnterior->every(fn($j) => $j->gols_casa !== null && $j->gols_fora !== null)) {
                $this->gerarSemifinal($campeonato);
            }
        }

        return $jogo;
    }
}
