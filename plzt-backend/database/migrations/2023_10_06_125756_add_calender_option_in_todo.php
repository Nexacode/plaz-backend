<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCalenderOptionInTodo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('todos', function (Blueprint $table) {
            if (!Schema::hasColumn('todos', 'in_calendar')) {
                $table->tinyInteger('in_calendar')->default(true)->after('status');
            }
            if (!Schema::hasColumn('todos', 'parent_index')) {
                $table->Integer('parent_index')->unsigned();
            }

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
            $table->dropColumn('in_calendar');
            $table->dropColumn('parent_index');
        });
    }
}
