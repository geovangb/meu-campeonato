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
class Jogo extends Model
{
    use HasFactory;

    protected $table = 'jogos';

    protected $fillable = [
        'campeonato_id',
        'time_casa_id',
        'time_fora_id',
        'partida',
        'data_partida',
        'local',
        'juiz',
        'auxiliar_1',
        'auxiliar_2',
        'gols_casa',
        'gols_fora',
        'escalacao_time_1',
        'reservas_time_1',
        'substituicao_time_1',
        'escalacao_time_2',
        'reservas_time_2',
        'substituicao_time_2',
        'sumula',
    ];

    protected $casts = [
        'data_partida' => 'datetime',
        'escalacao_time_1' => 'array',
        'reservas_time_1' => 'array',
        'substituicao_time_1' => 'array',
        'escalacao_time_2' => 'array',
        'reservas_time_2' => 'array',
        'substituicao_time_2' => 'array',
        'sumula' => 'array',
    ];

    public function getPlacarAttribute()
    {
        return "{$this->gols_casa} x {$this->gols_fora}";
    }

    public function campeonato()
    {
        return $this->belongsTo(Campeonato::class, 'campeonato_id');
    }

    public function timeCasa()
    {
        return $this->belongsTo(Time::class, 'time_casa_id');
    }

    public function timeFora()
    {
        return $this->belongsTo(Time::class, 'time_fora_id');
    }

}
