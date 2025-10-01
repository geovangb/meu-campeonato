<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRegrasToCampeonatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campeonatos', function (Blueprint $table) {
            $table->boolean('penaltis')->default(0)->after('qtd_times');
            $table->boolean('prorrogacao')->default(0)->after('penaltis');
            $table->boolean('criterio_desempate')->default(0)->after('prorrogacao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campeonatos', function (Blueprint $table) {
            $table->dropColumn(['penaltis', 'prorrogacao', 'criterio_desempate']);
        });
    }
}
