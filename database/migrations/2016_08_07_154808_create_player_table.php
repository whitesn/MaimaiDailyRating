<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerTable extends Migration
{
    public function up()
    {
        Schema::create('Player', function (Blueprint $table) {
            $table->string('name', 100);
            $table->float('rating');
            $table->integer('arcade_id')->references('id')->on('arcade');
        });
    }

    public function down()
    {
        Schema::table('Player', function($table) {
            $table->dropForeign('arcade_id');
        });

        Schema::drop('Player');
    }
}
