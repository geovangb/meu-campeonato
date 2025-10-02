<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFaseToJogosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jogos', function (Blueprint $table) {
            $table->string('fase')->nullable()->after('partida')
                ->comment('Indica a fase do jogo: quartas, semifinal, final, etc.');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jogos', function (Blueprint $table) {
            $table->dropColumn('fase');
        });
    }
}
