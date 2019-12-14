<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTunjanganRoles2Table extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		  Schema::dropIfExists('tunjangan_roles_2');
        Schema::create('tunjangan_roles_2', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tunjangan_id');
            $table->integer('role_id');
            $table->double('lokal');
            $table->double('non_lokal');
            $table->double('luar_jawa');
            $table->double('internasional');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tunjangan_roles_2');
    }
}
