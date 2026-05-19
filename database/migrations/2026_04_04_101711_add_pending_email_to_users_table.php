<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPendingEmailToUsersTable extends Migration
{
    public function up(){ //para possibilitar a verificação de email ao mudar
        Schema::table('users', function (Blueprint $table) {
            $table->string('pending_email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
