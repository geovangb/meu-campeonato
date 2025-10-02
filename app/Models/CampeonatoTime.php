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
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampeonatoTime extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'campeonato_id',
        'time_id',
        'vitoria',
        'derrota',
        'empate',
        'gols_feitos',
        'gols_sofridos',
        'cartao_amarelo',
        'cartao_vermelho',
        'jogos',
    ];

    /**
     * @return BelongsTo
     */
    public function campeonato()
    {
        return $this->belongsTo(Campeonato::class);
    }

    /**
     * @return BelongsTo
     */
    public function time()
    {
        return $this->belongsTo(Time::class);
    }
}
