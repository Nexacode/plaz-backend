<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('start',19)->nullable();
            $table->string('end',19)->nullable();
            $table->boolean('approved')->default(0);    
            $table->string('color',7)->nullable();
            $table->string('contraction',4)->nullable();
            $table->string('user_name');
            $table->string('user_id');   
            $table->datetime('from')->nullable(); 
            $table->datetime('to')->nullable();  
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
        Schema::dropIfExists('holidays');
    }
}
