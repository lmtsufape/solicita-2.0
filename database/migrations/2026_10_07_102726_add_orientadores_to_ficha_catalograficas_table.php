<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrientadoresToFichaCatalograficasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ficha_catalograficas', function (Blueprint $table) {
            $table->string('nome_orientador')->nullable();
            $table->string('sobrenome_orientador')->nullable();
            $table->string('nome_coorientador')->nullable();
            $table->string('sobrenome_coorientador')->nullable();
            $table->string('nome_segundo_coorientador')->nullable();
            $table->string('sobrenome_segundo_coorientador')->nullable();
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
            $table->dropColumn([
                'nome_orientador',
                'sobrenome_orientador',
                'nome_coorientador',
                'sobrenome_coorientador',
                'nome_segundo_coorientador',
                'sobrenome_segundo_coorientador',
            ]);
        });
    }
}
