<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateApprovalHistoriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('approval_histories', function ($table) {
            // $table->integer('transaction_type');
            // $table->integer('user_id');
            // $table->integer('approval_id');
            // $table->integer('approval_status')->default(0);
            // $table->renameColumn('timesheet_id', 'transaction_id');
        });
    }

}
