<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCategoriesAddEventExtras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('start',19)->after('name')->nullable();
            $table->string('end',19)->after('start')->nullable();
            $table->datetime('start_date')->after('end')->nullable();
            $table->datetime('end_date')->after('start_date')->nullable(); 
            $table->string('color',7)->after('end_date')->nullable();   
            $table->string('color_text',7)->after('color')->nullable();
            $table->string('color_border',7)->after('color_text')->nullable();  
            $table->string('color_background',7)->after('color_border')->nullable();     
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
			$table->dropColumn('start');
			$table->dropColumn('end');
			$table->dropColumn('start_date');
			$table->dropColumn('end_date');
			$table->dropColumn('color');
			$table->dropColumn('color_text');
			$table->dropColumn('color_border');
			$table->dropColumn('color_background');
        });
    }
}
