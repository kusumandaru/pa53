<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateTimesheetDetailTableModeration extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('timesheet_details', function ($table) {
            $table->smallInteger('approval_status')->default(0);
            $table->dateTime('moderated_at')->nullable();
            //If you want to track who moderated the Model add 'moderated_by' too.
            $table->integer('approval_id')->nullable()->unsigned();
        });
    }

}
