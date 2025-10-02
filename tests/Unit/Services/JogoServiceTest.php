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

namespace Tests\Unit\Services;

use App\DTOs\JogoDTO;
use App\DTOs\BulkUpdateJogoDatesDTO;
use App\Models\Campeonato;
use App\Models\Jogo;
use App\Models\Time;
use App\Services\JogoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class JogoServiceTest extends TestCase
{
    use RefreshDatabase;

    private JogoService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new JogoService();
    }

    /** @test */
    public function it_creates_a_jogo()
    {
        $timeCasa = Time::factory()->create();
        $timeFora = Time::factory()->create();
        $campeonato = Campeonato::factory()->create();

        $dto = new JogoDTO([
            'campeonato_id' => $campeonato->id,
            'time_casa_id' => $timeCasa->id,
            'time_fora_id' => $timeFora->id,
            'fase' => 'quartas',
            'data_partida' => Carbon::tomorrow(),
        ]);

        $jogo = $this->service->create($dto);

        $this->assertDatabaseHas('jogos', [
            'id' => $jogo->id,
            'campeonato_id' => $campeonato->id,
        ]);
    }

    /** @test */
    public function it_updates_a_jogo_and_generates_semifinal()
    {
        $campeonato = Campeonato::factory()->create();
        $time1 = Time::factory()->create();
        $time2 = Time::factory()->create();
        $time3 = Time::factory()->create();
        $time4 = Time::factory()->create();

        // Criar jogos de quartas
        $jogo1 = Jogo::factory()->create([
            'campeonato_id' => $campeonato->id,
            'time_casa_id' => $time1->id,
            'time_fora_id' => $time2->id,
            'fase' => 'quartas',
        ]);
        $jogo2 = Jogo::factory()->create([
            'campeonato_id' => $campeonato->id,
            'time_casa_id' => $time3->id,
            'time_fora_id' => $time4->id,
            'fase' => 'quartas',
        ]);

        $dto = new JogoDTO([
            'gols_casa' => 2,
            'gols_fora' => 1,
        ]);

        $updated = $this->service->updateJogo($jogo1, $dto);

        $this->assertEquals(2, $updated->gols_casa);
        $this->assertEquals(1, $updated->gols_fora);
    }

    /** @test */
    public function it_deletes_a_jogo()
    {
        $jogo = Jogo::factory()->create();

        $this->service->delete($jogo);

        $this->assertDatabaseMissing('jogos', ['id' => $jogo->id]);
    }

    /** @test */
    public function it_generates_quartas_jogos()
    {
        $campeonato = Campeonato::factory()->create();
        $times = Time::factory()->count(4)->create();
        $campeonato->times()->attach($times);

        $jogos = $this->service->gerarJogosQuartas($campeonato);

        $this->assertCount(4, $jogos);
        $this->assertDatabaseHas('jogos', [
            'campeonato_id' => $campeonato->id,
            'fase' => 'quartas',
        ]);
    }

    /** @test */
    public function it_bulk_updates_jogo_dates()
    {
        $jogo1 = Jogo::factory()->create();
        $jogo2 = Jogo::factory()->create();

        $dto = new BulkUpdateJogoDatesDTO([
            'jogos' => [
                ['id' => $jogo1->id, 'data_partida' => Carbon::tomorrow()],
                ['id' => $jogo2->id, 'data_partida' => Carbon::tomorrow()->addDay()],
                ['id' => 9999, 'data_partida' => Carbon::tomorrow()],
            ]
        ]);

        $results = $this->service->bulkUpdateDates($dto);

        $this->assertCount(3, $results);
        $this->assertEquals('success', $results[0]['status']);
        $this->assertEquals('error', $results[2]['status']);
    }
}
