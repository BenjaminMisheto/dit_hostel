<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('floor_id');
            $table->string('room_number');
            $table->string('gender')->nullable();
            $table->timestamps();

            $table->foreign('floor_id')->references('id')->on('floors')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}

