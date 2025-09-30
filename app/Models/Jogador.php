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

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jogador extends Model
{
    use HasFactory;

    protected $table = 'jogadores';

    protected $fillable = [
        'time_id',
        'nome',
        'nascimento',
        'altura',
        'peso',
        'posicao',
        'apto',
        'foto',
    ];

    protected $casts = [
        'nascimento' => 'date',
    ];

    public function time()
    {
        return $this->belongsTo(Time::class);
    }

}
