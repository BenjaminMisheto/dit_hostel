<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublishesTable extends Migration
{
    public function up()
    {
        Schema::create('publishes', function (Blueprint $table) {
            $table->id();
    $table->boolean('status')->default(false);
    $table->boolean('algorithm')->default(false);         // Corrected from 'algorithim'
    $table->boolean('reserved_bed')->default(false);      // Corrected from 'recerved _bed'
    $table->boolean('maintenance_bed')->default(false);
    $table->integer('expiration_date')->default(1);
    $table->date('open_date')->nullable();
    $table->date('report_date')->nullable();
    $table->date('deadline')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('publishes');
    }
}
