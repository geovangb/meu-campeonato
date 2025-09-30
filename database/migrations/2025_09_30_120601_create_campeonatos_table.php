<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampeonatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campeonatos', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 200);
            $table->boolean('status')->default(1);
            $table->date('data')->nullable();
            $table->integer('qtd_times')->default(0);
            $table->string('campeao', 200)->nullable();
            $table->string('vice', 200)->nullable();
            $table->string('terceiro_lugar', 200)->nullable();
            $table->string('tipo_campeonato', 100)->nullable();
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
        Schema::dropIfExists('campeonatos');
    }
}
