<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTodoEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('todo_events', function (Blueprint $table) {
            $table->bigInteger('todo_id')->unsigned()->nullable()->change();
            $table->string('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('todo_events', function (Blueprint $table) {
            $table->bigInteger('todo_id')->unsigned()->change();
            $table->dropColumn('title');
        });
    }
}
