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

namespace App\Http\Controllers;

use App\Models\Campeonato;
use Illuminate\Http\Request;
class CampeonatoController
{
    public function index()
    {
        $campeonatos = Campeonato::latest()->paginate(10);

        return view('campeonatos.index', compact('campeonatos'));
    }

    public function create()
    {
        return view('campeonatos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:200',
            'status' => 'boolean',
            'data' => 'nullable|date',
            'qtd_times' => 'nullable|integer|min:0',
            'tipo_campeonato' => 'nullable|string|max:100',
        ]);

        Campeonato::create($validated);

        return redirect()->route('campeonatos.index')->with('success', 'Campeonato criado com sucesso!');
    }

    public function edit(Campeonato $campeonato)
    {
        return view('campeonatos.edit', compact('campeonato'));
    }

    public function update(Request $request, Campeonato $campeonato)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:200',
            'status' => 'boolean',
            'data' => 'nullable|date',
            'qtd_times' => 'nullable|integer|min:0',
            'tipo_campeonato' => 'nullable|string|max:100',
        ]);

        $campeonato->update($validated);

        return redirect()->route('campeonatos.index')->with('success', 'Campeonato atualizado com sucesso!');
    }

    public function destroy(Campeonato $campeonato)
    {
        $campeonato->delete();
        return redirect()->route('campeonatos.index')->with('success', 'Campeonato excluído com sucesso!');
    }

    public function iniciar(Campeonato $campeonato)
    {
        if ($campeonato->jogos()->count() === 0) {

            app(JogoService::class)->gerarJogosQuartas($campeonato);
        }

        $primeiroJogo = $campeonato->jogos()->first();

        return redirect()->route('jogos.edit', [$campeonato->id, $primeiroJogo->id]);
    }

    public function jogos(Campeonato $campeonato)
    {
        $jogos = $campeonato->jogos()->with(['timeCasa', 'timeFora'])->get();

        $times = $campeonato->times()->get()->map(function($time) use ($campeonato) {
            $pontos = 0;
            $golsPro = 0;
            $golsContra = 0;
            $cartoes = 0;

            foreach($time->jogos() as $jogo) {
                if($jogo->time_casa_id == $time->id) {
                    $golsPro += $jogo->gols_casa ?? 0;
                    $golsContra += $jogo->gols_fora ?? 0;
                } else {
                    $golsPro += $jogo->gols_fora ?? 0;
                    $golsContra += $jogo->gols_casa ?? 0;
                }
                // exemplo de cartão
                $cartoes += $jogo->sumula['cartoes'][$time->id] ?? 0;

                $pontos += ($jogo->gols_casa ?? 0) - ($jogo->gols_fora ?? 0);
            }

            $time->pontos = $pontos;
            $time->gols_pro = $golsPro;
            $time->gols_contra = $golsContra;
            $time->cartoes = $cartoes;
            return $time;
        })->sortByDesc('pontos');

        $classificados = $times->take(4)->pluck('id')->toArray();

        return view('campeonatos.jogos', compact('campeonato', 'jogos', 'times', 'classificados'));
    }
}
