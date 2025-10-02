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

class Time extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'score',
        'status',
        'localidade',
        'responsavel',
    ];

    public function jogadores()
    {
        return $this->hasMany(Jogador::class);
    }

    public function campeonatoTimes()
    {
        return $this->hasMany(CampeonatoTime::class);
    }

    public function campeonatos()
    {
        return $this->belongsToMany(Campeonato::class, 'campeonato_times')
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

    public function jogosCasa()
    {
        return $this->hasMany(Jogo::class, 'time_casa_id');
    }

    public function jogosFora()
    {
        return $this->hasMany(Jogo::class, 'time_fora_id');
    }
}
