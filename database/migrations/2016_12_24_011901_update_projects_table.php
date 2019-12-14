<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateProjectsTable3 extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function ($table) {
            $table->dropColumn('code');
        });
        Schema::table('projects', function ($table) {
            $table->string('code');
        });
    }

}
