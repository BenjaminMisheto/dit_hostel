<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckOutItemsTable extends Migration
{
    public function up()
    {
        Schema::create('check_out_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->onDelete('set null');
            $table->string('name');
            $table->string('condition'); // 'Good' or 'Bad'
            $table->foreignId('block_id')->constrained()->onDelete('cascade');
            $table->foreignId('floor_id')->constrained()->onDelete('cascade'); // Added floor_id
            $table->foreignId('room_id')->constrained()->onDelete('cascade');  // Added room_id
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('check_out_items');
    }
}
