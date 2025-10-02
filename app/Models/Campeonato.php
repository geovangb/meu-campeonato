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
use App\Enums\TipoCampeonato;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campeonato extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'nome',
        'status',
        'data',
        'qtd_times',
        'campeao',
        'vice',
        'terceiro_lugar',
        'tipo_campeonato',
        'penaltis',
        'prorrogacao',
        'criterio_desempate',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'status' => 'boolean',
        'data' => 'date',
        'tipo_campeonato' => TipoCampeonato::class,
        ];

    /**
     * @var array|int[]
     */
    public static array $mapaTimes = [
        'Fase de Grupos (32 times)' => 32,
        'Oitavas de Final (16 times)' => 16,
        'Copa Quartas de final (8 times)' => 8,
        'Semi Final (4 times)' => 4,
    ];

    /**
     * @param $value
     * @return void
     */
    public function setTipoCampeonatoAttribute($value)
    {
        $this->attributes['tipo_campeonato'] = $value;
        $this->attributes['qtd_times'] = self::$mapaTimes[$value] ?? 0;
    }

    /**
     * @return HasMany
     */
    public function campeonatoTimes()
    {
        return $this->hasMany(CampeonatoTime::class);
    }

    /**
     * @return BelongsToMany
     */
    public function times()
    {
        return $this->belongsToMany(Time::class, 'campeonato_times')
            ->withPivot([
                'vitoria',
                'derrota',
                'empate',
                'gols_feitos',
                'gols_sofridos',
                'cartao_amarelo',
                'cartao_vermelho',
                'jogos',
            ])
            ->withTimestamps();
    }

    /**
     * @return HasMany
     */
    public function jogos()
    {
        return $this->hasMany(Jogo::class, 'campeonato_id');
    }
}
