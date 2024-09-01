<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserToTodoStatusHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('todo_status_histories', function (Blueprint $table) {
            $table->string('user_id')->nullable()->after('todo_status_id');
            $table->string('user_name')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('todo_status_histories', function (Blueprint $table) {
            Schema::dropIfExists('user_id');
            Schema::dropIfExists('user_name');
        });
    }
}
