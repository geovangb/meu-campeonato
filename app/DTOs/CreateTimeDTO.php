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

namespace App\DTOs;

use Illuminate\Http\Request;

class CreateTimeDTO
{
    public string $nome;

    public function __construct(array $data)
    {
        $this->nome = $data['nome'];
    }

    public static function fromRequest(Request $request): self
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255'
        ]);

        return new self($validated);
    }
}
