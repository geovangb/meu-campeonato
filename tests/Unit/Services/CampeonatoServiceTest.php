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

use App\DTOs\CampeonatoDTO;
use App\Models\Campeonato;
use App\Models\Jogo;
use App\Models\Time;
use App\Services\CampeonatoService;
use App\Services\JogoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;
use Mockery;
class CampeonatoServiceTest extends TestCase
{
    use RefreshDatabase;

    private CampeonatoService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CampeonatoService();
    }

    /** @test */
    public function it_creates_a_campeonato()
    {
        $dto = new CampeonatoDTO([
            'nome' => 'Campeonato Teste',
            'ano' => 2025,
        ]);

        $campeonato = $this->service->create($dto);

        $this->assertDatabaseHas('campeonatos', [
            'id' => $campeonato->id,
            'nome' => 'Campeonato Teste',
        ]);
    }

    /** @test */
    public function it_updates_a_campeonato()
    {
        $campeonato = Campeonato::factory()->create(['nome' => 'Old Name']);

        $dto = new CampeonatoDTO(['nome' => 'New Name']);

        $updated = $this->service->update($campeonato, $dto);

        $this->assertEquals('New Name', $updated->nome);
        $this->assertDatabaseHas('campeonatos', ['id' => $campeonato->id, 'nome' => 'New Name']);
    }

    /** @test */
    public function it_inicia_campeonato_and_generates_quartas_if_no_jogos()
    {
        $campeonato = Campeonato::factory()->create();

        $jogoServiceMock = Mockery::mock(JogoService::class);
        $jogoServiceMock->shouldReceive('gerarJogosQuartas')
            ->once()
            ->with($campeonato)
            ->andReturn(collect([Jogo::factory()->make()]));

        $firstJogo = $this->service->iniciarCampeonato($campeonato, $jogoServiceMock);

        $this->assertInstanceOf(Jogo::class, $firstJogo);
    }

    /** @test */
    public function it_returns_first_jogo_if_campeonato_already_has_jogos()
    {
        $campeonato = Campeonato::factory()->create();
        $jogo = Jogo::factory()->create(['campeonato_id' => $campeonato->id]);

        $jogoServiceMock = Mockery::mock(JogoService::class);

        $firstJogo = $this->service->iniciarCampeonato($campeonato, $jogoServiceMock);

        $this->assertEquals($jogo->id, $firstJogo->id);
    }

    /** @test */
    public function it_calculates_classificacao_correctly()
    {
        $campeonato = Campeonato::factory()->create();
        $times = Time::factory()->count(2)->create();
        $campeonato->times()->attach($times);

        // Criar jogos com gols e sumula
        $jogo = Jogo::factory()->create([
            'campeonato_id' => $campeonato->id,
            'time_casa_id' => $times[0]->id,
            'time_fora_id' => $times[1]->id,
            'gols_casa' => 3,
            'gols_fora' => 1,
            'sumula' => [
                'cartoes' => [
                    $times[0]->id => 1,
                    $times[1]->id => 2
                ]
            ]
        ]);

        $classificacao = $this->service->calcularClassificacao($campeonato);

        $this->assertCount(2, $classificacao);
        $this->assertEquals($times[0]->id, $classificacao[0]['time']->id);
        $this->assertEquals(2, $classificacao[0]['pontos']); // 3-1
        $this->assertEquals(1, $classificacao[0]['cartoes']);
    }

    /** @test */
    public function it_returns_top4_ids()
    {
        $times = collect([
            (object)['time' => (object)['id' => 10]],
            (object)['time' => (object)['id' => 20]],
            (object)['time' => (object)['id' => 30]],
            (object)['time' => (object)['id' => 40]],
            (object)['time' => (object)['id' => 50]],
        ]);

        $top4 = $this->service->top4Ids($times);

        $this->assertEquals([10,20,30,40], $top4);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
