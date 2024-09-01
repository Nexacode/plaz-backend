<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTodoStartEnd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::table('todos', function (Blueprint $table) {
    	
			$table->dropColumn('start');
    		$table->dropColumn('end');
        	 	
        });
        
    	Schema::table('todos', function (Blueprint $table) {
    	
    		$table->datetime('start')->nullable();
    		$table->datetime('end')->nullable();
        	 	
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
			$table->dropColumn('start');
    		$table->dropColumn('end');
        });
    	Schema::table('todos', function (Blueprint $table) {
            $table->string('start',18)->nullable();
            $table->string('end',18)->nullable();
        });
    }
}
