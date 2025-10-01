<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampeonatoTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campeonato_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campeonato_id')->constrained()->cascadeOnDelete();
            $table->foreignId('time_id')->constrained()->cascadeOnDelete();
            $table->integer('vitoria')->default(0);
            $table->integer('derrota')->default(0);
            $table->integer('empate')->default(0);
            $table->integer('gols_feitos')->default(0);
            $table->integer('gols_sofridos')->default(0);
            $table->integer('cartao_amarelo')->default(0);
            $table->integer('cartao_vermelho')->default(0);
            $table->integer('jogos')->default(0);
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
        Schema::dropIfExists('campeonato_times');
    }
}
