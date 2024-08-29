<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElligableStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elligable_students', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');
            $table->string('registration_number')->unique(); // Changed to string for consistency and uniqueness
            $table->enum('payment_status', ['pending', 'paid'])->default('pending');
            $table->string('sponsorship')->nullable(); // New field for sponsorship
            $table->string('phone')->nullable(); // New field for phone
            $table->enum('gender', ['Male', 'Female'])->nullable(); // New field for gender
            $table->string('nationality')->nullable(); // New field for nationality
            $table->string('course')->nullable(); // New field for course
            $table->string('email')->nullable(); // New field for course
            $table->string('image')->nullable();
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
        Schema::dropIfExists('elligable_students');
    }
}
