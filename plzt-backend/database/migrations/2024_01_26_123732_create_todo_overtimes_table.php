<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodoOvertimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todo_overtimes', function (Blueprint $table) {
            $table->id();
            $table->biginteger('todo_id');
            $table->string('estimation',6);
            $table->text('information')->nullable();
            $table->string('user_id')->nullable();
            $table->string('user_name')->nullable();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('todo_overtimes');
    }
}
