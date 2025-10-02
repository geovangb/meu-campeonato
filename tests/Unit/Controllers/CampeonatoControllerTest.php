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

namespace Tests\Unit\Controllers;

use App\Http\Controllers\CampeonatoController;
use App\Models\Campeonato;
use App\Services\CampeonatoService;
use App\Services\JogoService;
use App\DTOs\CampeonatoDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class CampeonatoControllerTest extends TestCase
{
    use RefreshDatabase;

    private $serviceMock;
    private $jogoServiceMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->serviceMock = Mockery::mock(CampeonatoService::class);
        $this->jogoServiceMock = Mockery::mock(JogoService::class);
    }

    /** @test */
    public function index_returns_view_with_campeonatos()
    {
        Campeonato::factory()->count(3)->create();

        $response = $this->get(route('campeonatos.index'));

        $response->assertStatus(200);
        $response->assertViewIs('campeonatos.index');
        $response->assertViewHas('campeonatos');
    }

    /** @test */
    public function create_returns_view()
    {
        $response = $this->get(route('campeonatos.create'));

        $response->assertStatus(200);
        $response->assertViewIs('campeonatos.create');
    }

    /** @test */
    public function store_calls_service_and_redirects()
    {
        $this->serviceMock
            ->shouldReceive('create')
            ->once()
            ->andReturn(Campeonato::factory()->make());

        $controller = new CampeonatoController($this->serviceMock, $this->jogoServiceMock);

        $request = Request::create(route('campeonatos.store'), 'POST', [
            'nome' => 'Campeonato Test',
            'ano' => 2025,
        ]);

        $response = $controller->store($request);

        $this->assertEquals(302, $response->getStatusCode());
    }

    /** @test */
    public function iniciar_redirects_to_first_jogo()
    {
        $campeonato = Campeonato::factory()->create();
        $primeiroJogo = (object) ['id' => 10];

        $this->serviceMock
            ->shouldReceive('iniciarCampeonato')
            ->once()
            ->with($campeonato, Mockery::any())
            ->andReturn($primeiroJogo);

        $controller = new CampeonatoController($this->serviceMock, $this->jogoServiceMock);

        $response = $controller->iniciar($campeonato);

        $response->assertRedirect(route('jogos.edit', [$campeonato->id, $primeiroJogo->id]));
    }

    /** @test */
    public function jogos_returns_view_with_classificacao_and_top4()
    {
        $campeonato = Campeonato::factory()->create();

        $this->serviceMock
            ->shouldReceive('calcularClassificacao')
            ->once()
            ->andReturn(collect());
        $this->serviceMock
            ->shouldReceive('top4Ids')
            ->once()
            ->andReturn([]);

        $controller = new CampeonatoController($this->serviceMock, $this->jogoServiceMock);

        $response = $controller->jogos($campeonato);

        $response->assertStatus(200);
        $response->assertViewIs('campeonatos.jogos');
        $response->assertViewHasAll(['campeonato','jogos','classificacao','top4']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
