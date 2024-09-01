<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::table('users', function($table) {         
            $table->string('keycloak_id')->nullable();
            $table->integer('holidays')->nullable();
            $table->string('color',7)->nullable();
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::table('users', function($table) {
        	$table->dropColumn('keycloak_id');
        	$table->dropColumn('holidays');
        	$table->dropColumn('color');
        });
    }
}
