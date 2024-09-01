<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTodoCalendarEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->string('start',19)->nullable();
            $table->string('end',19)->nullable();
            $table->string('color',20)->nullable();
            $table->smallInteger('checking_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::table('todos', function($table) {
        	$table->dropColumn('start');
        	$table->dropColumn('end');
			$table->dropColumn('color');
			$table->dropColumn('chicking_status');
        });
    }
}
