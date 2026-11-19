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
        if (Schema::hasTable('tipo_documentos') && !Schema::hasTable('tipos_fichas_catalograficas')) {
            Schema::rename('tipo_documentos', 'tipos_fichas_catalograficas');
        }

        if (Schema::hasColumn('tipos_fichas_catalograficas', 'tipo') && !Schema::hasColumn('tipos_fichas_catalograficas', 'nome')) {
            Schema::table('tipos_fichas_catalograficas', function (Blueprint $table) {
                $table->renameColumn('tipo', 'nome');
            });
        }

        if (!Schema::hasColumn('tipos_fichas_catalograficas', 'slug')) {
            Schema::table('tipos_fichas_catalograficas', function (Blueprint $table) {
                $table->string('slug')->nullable()->unique()->after('nome');
            });
        }

        if (Schema::hasColumn('ficha_catalograficas', 'tipo_documento_id')) {
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                Schema::table('ficha_catalograficas', function (Blueprint $table) {
                    $table->dropForeign(['tipo_documento_id']);
                });
            }

            Schema::table('ficha_catalograficas', function (Blueprint $table) {
                $table->renameColumn('tipo_documento_id', 'tipo_ficha_catalografica_id');
            });

            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                Schema::table('ficha_catalograficas', function (Blueprint $table) {
                    $table->foreign('tipo_ficha_catalografica_id')
                        ->references('id')
                        ->on('tipos_fichas_catalograficas');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('ficha_catalograficas', 'tipo_ficha_catalografica_id')) {
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                Schema::table('ficha_catalograficas', function (Blueprint $table) {
                    $table->dropForeign(['tipo_ficha_catalografica_id']);
                });
            }

            Schema::table('ficha_catalograficas', function (Blueprint $table) {
                $table->renameColumn('tipo_ficha_catalografica_id', 'tipo_documento_id');
            });
        }

        if (Schema::hasColumn('tipos_fichas_catalograficas', 'slug')) {
            Schema::table('tipos_fichas_catalograficas', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }

        if (Schema::hasColumn('tipos_fichas_catalograficas', 'nome') && !Schema::hasColumn('tipos_fichas_catalograficas', 'tipo')) {
            Schema::table('tipos_fichas_catalograficas', function (Blueprint $table) {
                $table->renameColumn('nome', 'tipo');
            });
        }

        if (Schema::hasTable('tipos_fichas_catalograficas') && !Schema::hasTable('tipo_documentos')) {
            Schema::rename('tipos_fichas_catalograficas', 'tipo_documentos');
        }

        if (Schema::hasColumn('ficha_catalograficas', 'tipo_documento_id') && Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::table('ficha_catalograficas', function (Blueprint $table) {
                $table->foreign('tipo_documento_id')
                    ->references('id')
                    ->on('tipo_documentos');
            });
        }
    }
}
