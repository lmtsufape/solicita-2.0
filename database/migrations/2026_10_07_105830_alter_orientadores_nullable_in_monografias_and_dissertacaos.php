<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrientadoresNullableInMonografiasAndDissertacaos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (['monografias', 'dissertacaos'] as $tabela) {
            Schema::table($tabela, function (Blueprint $table) {
                $table->string('nome_orientador')->nullable()->change();
                $table->string('sobrenome_orientador')->nullable()->change();
                $table->string('nome_coorientador')->nullable()->change();
                $table->string('sobrenome_coorientador')->nullable()->change();
                $table->string('nome_segundo_coorientador')->nullable()->change();
                $table->string('sobrenome_segundo_coorientador')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (['monografias', 'dissertacaos'] as $tabela) {
            Schema::table($tabela, function (Blueprint $table) {
                $table->string('nome_orientador')->nullable(false)->change();
                $table->string('sobrenome_orientador')->nullable(false)->change();
            });
        }
    }
}
