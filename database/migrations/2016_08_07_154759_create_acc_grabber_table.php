<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccGrabberTable extends Migration
{
    public function up()
    {
        Schema::create('Grabber', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 100)->unique();
            $table->string('password', 100);
        });
    }

    public function down()
    {
        Schema::drop('Grabber');
    }
}
