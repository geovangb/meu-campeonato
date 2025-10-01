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

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jogo;

class JogoApiController extends Controller
{
    public function updateDate(Request $request, Jogo $jogo)
    {
        $data = $request->validate([
            'data_partida' => 'required|date',
        ]);

        $jogo->update(['data_partida' => $data['data_partida']]);

        return response()->json(['success' => true, 'jogo' => $jogo]);
    }

    public function bulkUpdateDates(Request $request)
    {
        $items = $request->input('items', []);
        $updated = [];
        foreach ($items as $it) {
            $j = Jogo::find($it['id']);
            if ($j) {
                $j->update(['data_partida' => $it['data_partida']]);
                $updated[] = $j;
            }
        }
        return response()->json(['success' => true, 'updated' => $updated]);
    }

    public function update(Request $request, Jogo $jogo, JogoService $service)
    {
        $jogo->update($request->only('gols_casa','gols_fora'));

        $jogo->timeCasa->increment('pontos', ($jogo->gols_casa ?? 0) - ($jogo->gols_fora ?? 0));
        $jogo->timeFora->increment('pontos', ($jogo->gols_fora ?? 0) - ($jogo->gols_casa ?? 0));

        $campeonato = $jogo->campeonato;
        $faseAnterior = 'quartas';
        $jogosFaseAnterior = $campeonato->jogos()->where('fase', $faseAnterior)->get();

        $proximaFase = null;

        if($jogosFaseAnterior->every(fn($j) => $j->gols_casa !== null && $j->gols_fora !== null)) {
            $service->gerarSemifinal($campeonato);
            $proximaFase = 'semifinal';
        }

        $confrontos = $proximaFase ? $campeonato->jogos()->where('fase',$proximaFase)->get()->map(function($j) {
            return [
                'time1' => $j->timeCasa->nome,
                'time2' => $j->timeFora->nome,
                'jogo_ida' => $j->id,
                'data_ida' => $j->data_partida->toDateTimeString()
            ];
        }) : [];

        return response()->json([
            'success' => true,
            'confrontos_proxima_fase' => $confrontos
        ]);
    }
}
