<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameClassificacaoToAreaConhecimentoInFichaCatalograficasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ficha_catalograficas', function (Blueprint $table) {
            $table->renameColumn('classificacao', 'area_conhecimento');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ficha_catalograficas', function (Blueprint $table) {
            $table->renameColumn('area_conhecimento', 'classificacao');
        });
    }
}
