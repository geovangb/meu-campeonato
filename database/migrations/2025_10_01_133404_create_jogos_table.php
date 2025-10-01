<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJogosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jogos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('campeonato_id');
            $table->unsignedBigInteger('time_casa_id');
            $table->unsignedBigInteger('time_fora_id');

            $table->integer('partida')->default(1); // 1 = ida, 2 = volta
            $table->dateTime('data_partida')->nullable();
            $table->string('local')->nullable();
            $table->string('juiz')->nullable();
            $table->string('auxiliar_1')->nullable();
            $table->string('auxiliar_2')->nullable();

            $table->integer('gols_casa')->nullable();
            $table->integer('gols_fora')->nullable();

            // Escalações e súmula (JSON/texto)
            $table->longText('escalacao_time_1')->nullable();
            $table->longText('reservas_time_1')->nullable();
            $table->longText('substituicao_time_1')->nullable();

            $table->longText('escalacao_time_2')->nullable();
            $table->longText('reservas_time_2')->nullable();
            $table->longText('substituicao_time_2')->nullable();

            $table->longText('sumula')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jogos');
    }
}
