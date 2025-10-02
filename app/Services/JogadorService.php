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

use App\DTOs\JogadorDTO;
use App\Models\Jogador;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
class JogadorService
{
    /**
     * @param JogadorDTO $dto
     * @param UploadedFile|null $foto
     * @return Jogador
     */
    public function criar(JogadorDTO $dto, ?UploadedFile $foto = null): Jogador
    {
        if ($foto) {
            $dto->foto = $foto->store('jogadores', 'public');
        }

        return Jogador::create($dto->toArray());
    }

    /**
     * @param Jogador $jogador
     * @param JogadorDTO $dto
     * @param UploadedFile|null $foto
     * @return Jogador
     */
    public function atualizar(Jogador $jogador, JogadorDTO $dto, ?UploadedFile $foto = null): Jogador
    {
        if ($foto) {
            if ($jogador->foto) {
                Storage::disk('public')->delete($jogador->foto);
            }
            $dto->foto = $foto->store('jogadores', 'public');
        }

        $jogador->update($dto->toArray());

        return $jogador;
    }

    /**
     * @param Jogador $jogador
     * @return void
     */
    public function remover(Jogador $jogador): void
    {
        if ($jogador->foto) {
            Storage::disk('public')->delete($jogador->foto);
        }
        $jogador->delete();
    }
}
