<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('project_id')->unsigned()->nullable();
            $table->bigInteger('milestone_id')->unsigned()->nullable();
            $table->string('todo')->nullable();
            $table->boolean('discussed',false)->nullable();
            $table->string('employee',4)->nullable();
            $table->datetime('date')->nullable();
            $table->string('estimated_time',6)->nullable();
            $table->string('calculated_time',6)->nullable();
            $table->datetime('deadline')->nullable();
            $table->boolean('status',false)->nullable();
            $table->datetime('online_test')->nullable();
            $table->datetime('online_live')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('todos');
    }
}
