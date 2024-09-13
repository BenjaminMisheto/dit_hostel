<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->foreignId('semester_id')->nullable()->constrained('semesters')->onDelete('set null');
    $table->string('name');
    $table->integer('registration_number')->nullable();
    $table->integer('counter')->default(0);
    $table->integer('checkin')->default(0);
    $table->integer('checkout')->default(0);
    $table->boolean('confirmation')->default(0);
    $table->boolean('afterpublish')->nullable();
    $table->boolean('application')->nullable();
    $table->string('status')->default('disapproved'); // Add status column with default value
    $table->bigInteger('payment_status')->nullable();
    $table->bigInteger('Control_Number')->nullable();

    $table->integer('block_id')->nullable();
    $table->integer('room_id')->nullable();
    $table->integer('floor_id')->nullable(); // New field for floor
    $table->integer('bed_id')->nullable();   // New field for bed
    $table->string('sponsorship')->nullable(); // New field for sponsorship
    $table->string('phone')->nullable();       // New field for phone
    $table->string('gender')->nullable();      // New field for gender
    $table->string('nationality')->nullable(); // New field for nationality
    $table->string('course')->nullable();      // New field for course
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password')->default(Hash::make('12345678'));
    $table->rememberToken();
    $table->foreignId('current_team_id')->nullable();
    $table->string('profile_photo_path', 2048)->nullable();
    $table->timestamp('expiration_date')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
