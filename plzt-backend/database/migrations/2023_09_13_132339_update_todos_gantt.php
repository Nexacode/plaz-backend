<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTodosGantt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::table('todos', function (Blueprint $table) {	
    		$table->string('duration',10)->nullable();
    		$table->string('duration_unit',10)->nullable();
    		$table->string('effort',10)->nullable();
    		$table->string('effort_unit',10)->nullable();  	
    		$table->boolean('effort_driven')->default(false);
    		$table->string('percent_done',6)->nullable(); 
    		$table->text('note')->nullable();
    		$table->datetime('constraint_date')->nullable();  
    		$table->string('constraint_type',100)->nullable();
    		$table->datetime('early_end_date')->nullable();
    		$table->datetime('early_start_date')->nullable();
    		$table->string('parent_id',10)->nullable(); 
    		$table->string('parent_index',100)->nullable();
    		$table->string('ordered_parent_index',100)->nullable();
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
    		$table->dropColumn('effort');
    		$table->dropColumn('effort_unit');
    		$table->dropColumn('effort_driven');
       		$table->dropColumn('duration');
       		$table->dropColumn('duration_unit');
       		$table->dropColumn('note');
       		$table->dropColumn('percent_done');
       		$table->dropColumn('constraint_date');
       		$table->dropColumn('constraint_type');
       		$table->dropColumn('early_end_date');
       		$table->dropColumn('early_start_date');
       		$table->dropColumn('parent_id');
       		$table->dropColumn('parent_index');
       		$table->dropColumn('ordered_parent_index');
       	});
    }
}
