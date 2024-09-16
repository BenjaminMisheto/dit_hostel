<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlocksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('semester_id')->nullable()->constrained('semesters')->onDelete('set null');
            $table->string('name');
            $table->longText('image_data')->default('img/placeholder.jpg');
            $table->string('manager');
            $table->string('location');
            $table->boolean('status')->default(0); // Status column
            $table->integer('number_of_floors');
            $table->integer('price'); // Added column for block price
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocks');
    }
}
