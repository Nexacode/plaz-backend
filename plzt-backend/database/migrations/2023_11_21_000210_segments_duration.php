<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SegmentsDuration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('todo_segments', function (Blueprint $table) {
             $table->string('duration',10)->nullable()->after('end');
             $table->string('duration_unit',20)->nullable()->after('duration');
             $table->string('color',20)->nullable()->after('duration_unit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('todo_segments', function (Blueprint $table) {
        	$table->dropColumn('duration');
            $table->dropColumn('duration_unit');
            $table->dropColumn('color');
        });
    }
}
