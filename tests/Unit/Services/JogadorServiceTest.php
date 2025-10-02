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

use App\DTOs\JogadorDTO;
use App\Models\Jogador;
use App\Services\JogadorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class JogadorServiceTest extends TestCase
{
    use RefreshDatabase;

    private JogadorService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new JogadorService();
    }

    /** @test */
    public function it_creates_a_jogador_without_photo()
    {
        $dto = new JogadorDTO([
            'nome' => 'Jogador Teste',
            'idade' => 25,
            'posicao' => 'Atacante',
        ]);

        $jogador = $this->service->criar($dto);

        $this->assertDatabaseHas('jogadores', [
            'id' => $jogador->id,
            'nome' => 'Jogador Teste',
        ]);

        $this->assertNull($jogador->foto);
    }

    /** @test */
    public function it_creates_a_jogador_with_photo()
    {
        Storage::fake('public');

        $dto = new JogadorDTO([
            'nome' => 'Jogador Foto',
            'idade' => 22,
            'posicao' => 'Meio',
        ]);

        $foto = UploadedFile::fake()->image('foto.jpg');

        $jogador = $this->service->criar($dto, $foto);

        $this->assertDatabaseHas('jogadores', ['id' => $jogador->id]);
        Storage::disk('public')->assertExists($jogador->foto);
    }

    /** @test */
    public function it_updates_a_jogador_and_replaces_photo()
    {
        Storage::fake('public');

        $jogador = Jogador::factory()->create([
            'foto' => 'jogadores/old.jpg'
        ]);
        Storage::disk('public')->put('jogadores/old.jpg', 'conteudo antigo');

        $dto = new JogadorDTO([
            'nome' => 'Jogador Atualizado',
            'idade' => 28,
            'posicao' => 'Defensor',
        ]);

        $novaFoto = UploadedFile::fake()->image('nova.jpg');

        $updated = $this->service->atualizar($jogador, $dto, $novaFoto);

        $this->assertEquals('Jogador Atualizado', $updated->nome);
        Storage::disk('public')->assertMissing('jogadores/old.jpg');
        Storage::disk('public')->assertExists($updated->foto);
    }

    /** @test */
    public function it_removes_a_jogador_and_photo()
    {
        Storage::fake('public');

        $jogador = Jogador::factory()->create([
            'foto' => 'jogadores/foto.jpg'
        ]);

        Storage::disk('public')->put('jogadores/foto.jpg', 'conteudo');

        $this->service->remover($jogador);

        $this->assertDatabaseMissing('jogadores', ['id' => $jogador->id]);
        Storage::disk('public')->assertMissing('jogadores/foto.jpg');
    }
}
