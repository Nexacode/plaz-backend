<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateHolidays extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::table('holidays', function (Blueprint $table) {
            $table->string('holidays',4)->after('contraction');
            $table->string('days',4)->after('holidays');
            $table->string('days_left',4)->after('days');
            $table->boolean('year_before',false)->nullable()->after('days_left');
            $table->string('days_left_year_before',4)->after('year_before');
            $table->integer('status')->nullable()->after('days_left_year_before'); 
            $table->integer('holiday_year')->after('status');
    	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::table('holidays', function (Blueprint $table) {
			$table->dropColumn('holidays');
			$table->dropColumn('days');
			$table->dropColumn('days_left');
			$table->dropColumn('year_before');
			$table->dropColumn('days_left_year_before');
			$table->dropColumn('status');
			$table->dropColumn('holiday_year');
    	});
    }
}
