<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrientadoresNullableInTesesAndProgramaEducacionals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (['teses', 'programa_educacionals'] as $tabela) {
            Schema::table($tabela, function (Blueprint $table) {
                $table->string('nome_orientador')->nullable()->change();
                $table->string('sobrenome_orientador')->nullable()->change();
                $table->string('nome_coorientador')->nullable()->change();
                $table->string('sobrenome_coorientador')->nullable()->change();
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
        foreach (['teses', 'programa_educacionals'] as $tabela) {
            Schema::table($tabela, function (Blueprint $table) {
                $table->string('nome_orientador')->nullable(false)->change();
                $table->string('sobrenome_orientador')->nullable(false)->change();
            });
        }
    }
}
