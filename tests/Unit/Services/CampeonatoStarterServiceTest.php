<?php

namespace Tests\Unit\Services;
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
use App\Models\Campeonato;
use App\Models\Time;
use App\Services\CampeonatoStarterService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampeonatoStarterServiceTest extends TestCase
{
    use RefreshDatabase;

    private CampeonatoStarterService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CampeonatoStarterService::class);
    }

    /** @test */
    public function deve_sortear_times_e_criar_jogos_de_ida_e_volta()
    {
        // Arrange
        $campeonato = Campeonato::factory()->create([
            'qtd_times' => 4,
        ]);

        $times = Time::factory()->count(4)->create();

        // relaciona times ao campeonato
        $campeonato->times()->attach($times->pluck('id'));

        // Act
        $confrontos = $this->service->sortear($campeonato);

        // Assert
        $this->assertCount(2, $confrontos);
        foreach ($confrontos as $c) {
            $this->assertArrayHasKey('time1', $c);
            $this->assertArrayHasKey('time2', $c);
            $this->assertArrayHasKey('jogo_ida', $c);
            $this->assertArrayHasKey('jogo_volta', $c);

            $this->assertNotNull($c['data_ida']);
            $this->assertNotNull($c['data_volta']);
        }

        $this->assertDatabaseCount('jogos', 4);
    }

    /** @test */
    public function deve_lancar_excecao_quando_numero_de_times_for_impar()
    {
        $this->expectException(\Exception::class);

        $campeonato = Campeonato::factory()->create();
        $times = Time::factory()->count(3)->create();
        $campeonato->times()->attach($times->pluck('id'));

        $this->service->sortear($campeonato);
    }

    /** @test */
    public function deve_lancar_excecao_quando_nao_ha_times_suficientes()
    {
        $this->expectException(\Exception::class);

        $campeonato = Campeonato::factory()->create();
        // sem times vinculados

        $this->service->sortear($campeonato);
    }
}
