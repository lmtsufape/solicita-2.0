<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTipoDocumentosAndUpdateFichaCatalograficasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('tipo_documentos', 'tipos_fichas_catalograficas');

        Schema::table('tipos_fichas_catalograficas', function (Blueprint $table) {
            $table->renameColumn('tipo', 'nome');
            $table->string('slug')->nullable()->unique()->after('nome');
        });

        Schema::table('ficha_catalograficas', function (Blueprint $table) {
            $table->dropForeign(['tipo_documento_id']);
        });

        Schema::table('ficha_catalograficas', function (Blueprint $table) {
            $table->renameColumn('tipo_documento_id', 'tipo_ficha_catalografica_id');
        });

        Schema::table('ficha_catalograficas', function (Blueprint $table) {
            $table->foreign('tipo_ficha_catalografica_id')
                ->references('id')
                ->on('tipos_fichas_catalograficas');
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
            $table->dropForeign(['tipo_ficha_catalografica_id']);
        });

        Schema::table('ficha_catalograficas', function (Blueprint $table) {
            $table->renameColumn('tipo_ficha_catalografica_id', 'tipo_documento_id');
        });


        Schema::table('tipos_fichas_catalograficas', function (Blueprint $table) {
            $table->renameColumn('nome', 'tipo');
            $table->dropColumn('slug');
        });

        Schema::rename('tipos_fichas_catalograficas', 'tipo_documentos');

        Schema::table('ficha_catalograficas', function (Blueprint $table) {
            $table->foreign('tipo_documento_id')
                ->references('id')
                ->on('tipo_documentos');
        });
    }
}
