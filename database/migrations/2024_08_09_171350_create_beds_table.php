
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBedsTable extends Migration
{
    public function up()
    {
        // Drop the beds table if it exists to avoid conflicts
        Schema::dropIfExists('beds');

        // Create the beds table
        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->onDelete('set null');
            $table->unsignedBigInteger('room_id');
            $table->string('bed_number');
            $table->string('status')->default('activate'); // Adding the status field
            $table->unsignedBigInteger('user_id')->nullable(); // Adding the user_id column
            $table->timestamps();

            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null'); // Foreign key constraint for user_id
            // $table->unique('room_id'); // Ensure each bed number is unique within a room
        });
    }

    public function down()
    {Schema::dropIfExists('beds');
}
}
