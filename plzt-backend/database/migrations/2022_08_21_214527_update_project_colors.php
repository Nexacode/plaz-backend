<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProjectColors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
        	$table->string('color_text',7)->after('color')->nullable();
            $table->string('color_border',7)->after('color_text')->nullable();
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
            $table->dropColumn('color_border');
            $table->dropColumn('color_text');
        });
    }
}
