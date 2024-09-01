<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTodosUserInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::table('todos', function($table) {
            $table->string('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->dropColumn('employee');
          });    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::table('todos', function (Blueprint $table) {
	        $table->dropColumn('user_id');
	        $table->dropColumn('user_name');
	        $table->string('employee',4)->nullable();
	    });
    }
}
