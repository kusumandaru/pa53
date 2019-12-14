<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateLeavesTable2 extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leaves', function ($table) {
            $table->smallInteger('status')->default(0)->change();
            $table->dateTime('moderated_at')->nullable();
            //If you want to track who moderated the Model add 'moderated_by' too.
            $table->integer('moderated_by')->nullable()->unsigned();
        });
    }

}
