<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBattleSampleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('battle_sample', function (Blueprint $table) {
            $table->unsignedBigInteger('battle_id')->index();
            $table->foreign('battle_id')->references('id')->on('battles')->onDelete('cascade');

            $table->unsignedBigInteger('sample_id')->index();
            $table->foreign('sample_id')->references('id')->on('samples')->onDelete('cascade');

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
        Schema::dropIfExists('battle_sample');
    }
}
