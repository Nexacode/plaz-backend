<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTodoDependenciesChangeFromTo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('todo_dependencies', function (Blueprint $table) {
            $table->string('from')->nullable()->change();
            $table->string('to')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('todo_dependencies', function (Blueprint $table) {
            $table->integer('from')->nullable()->change();
            $table->interger('to')->nullable()->change();
        });
    }
}
