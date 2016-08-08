<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArcadeTable extends Migration
{
    public function up()
    {
        Schema::create('Arcade', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->unique();
        });
    }

    public function down()
    {
        Schema::drop('Arcade');
    }
}
