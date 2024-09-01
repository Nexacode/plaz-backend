<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMilestone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::table('milestones', function($table) {
			$table->renameColumn('user', 'user_name');
			//$table->string('user_name')->nullable()->change();
			$table->string('user_id')->nullable();
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
          Schema::table('milestones', function($table) {
			$table->renameColumn('user_name', 'user');
			//$table->integer('user')->nullable();
			$table->dropColumn('user_id');
			//$table->dropColumn('user_id');
          });
    }
}
