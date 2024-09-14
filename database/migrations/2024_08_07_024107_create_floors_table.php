<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFloorsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('floors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_id')->constrained('blocks')->onDelete('cascade');
            $table->string('floor_number'); // Ensure floor_number is unique
            $table->integer('number_of_rooms');
            $table->json('gender');  // Store gender as JSON
            $table->json('eligibility'); // Store eligibility as JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('floors');
    }
}
