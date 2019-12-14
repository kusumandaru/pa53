<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateProjectsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function ($table) {
            // $table->dropColumn('budget');
        });
        Schema::table('projects', function ($table) {
            // $table->decimal('budget', 15, 2);
            // $table->integer('effort_type');
        });
    }

}
