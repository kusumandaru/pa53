<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateApprovalHistoriesTable1 extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('approval_histories', function ($table) {
            // $table->dropColumn('transaction_type');
        });
        Schema::table('approval_histories', function ($table) {
            // $table->integer('transaction_type');
        });
    }

}
