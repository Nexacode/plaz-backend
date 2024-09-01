<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProjectGantt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::table('projects', function (Blueprint $table) {	
    		$table->datetime('start')->nullable();
    		$table->datetime('end')->nullable();    	
    		$table->string('duration',10)->nullable();
    		$table->string('duration_unit',10)->nullable();
    		$table->string('cls',255)->nullable();
    		$table->string('direction',255)->nullable();
    		$table->boolean('manually_scheduled')->default(false);
    		$table->string('constraint_type',100)->nullable();
    		$table->datetime('constraint_date')->nullable(); 
    		$table->string('effort',10)->nullable();
    		$table->string('effort_unit',10)->nullable();  	
    		$table->boolean('effort_driven')->default(false);
    		$table->string('percent_done',6)->nullable();
    		$table->boolean('expanded')->default(false); 
    		$table->text('note')->nullable();
    		$table->Integer('parent_index')->unsigned();
    		$table->string('scheduling_mode',20)->nullable(); 
    		$table->string('gantt_status',50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::table('projects', function (Blueprint $table) {
       		$table->dropColumn('start');
       		$table->dropColumn('end');  
       		$table->dropColumn('duration');
       		$table->dropColumn('duration_unit'); 
       		$table->dropColumn('cls');
       		$table->dropColumn('direction');
       		$table->dropColumn('manually_scheduled');
       		$table->dropColumn('constraint_type');
       		$table->dropColumn('constraint_date');
       		$table->dropColumn('effort');
    		$table->dropColumn('effort_unit');
    		$table->dropColumn('effort_driven');
       		$table->dropColumn('note');
       		$table->dropColumn('percent_done');
			$table->dropColumn('expanded');
			$table->dropColumn('note');
       		$table->dropColumn('parent_index');
       		$table->dropColumn('scheduling_mode');
       		$table->dropColumn('gantt_status');        		
       	});
    }
}
