<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSliderDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slider_data', function (Blueprint $table) {
    $table->id();
    $table->foreignId('semester_id')->nullable()->constrained('semesters')->onDelete('set null');
    $table->foreignId('block_id')->constrained('blocks')->onDelete('cascade');
    $table->foreignId('floor_id')->constrained('floors')->onDelete('cascade');
    $table->foreignId('bed_id')->constrained('beds')->onDelete('cascade');
    $table->string('criteria');
    $table->integer('status')->default('1');

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
        Schema::dropIfExists('slider_data');
    }
}
