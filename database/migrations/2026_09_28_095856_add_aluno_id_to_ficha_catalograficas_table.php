<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlunoIdToFichaCatalograficasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ficha_catalograficas', function (Blueprint $table) {
            $table->foreignId('aluno_id')->nullable()->constrained('alunos')->onDelete('cascade');
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
            $table->dropForeign('ficha_catalograficas_aluno_id_foreign');
            $table->dropColumn('aluno_id');
        });
    }
}
