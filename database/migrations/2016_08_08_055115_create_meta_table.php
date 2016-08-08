<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetaTable extends Migration
{
    public function up()
    {
        Schema::create('Meta', function (Blueprint $table) {
            $table->string('name');
            $table->string('value');
        });

        DB::table('meta')->insert([
            [ 'name' => 'LAST_UPDATE_TIME', 'value' => '0'],
        ]);
    }

    public function down()
    {
        Schema::drop('Meta');
    }
}
