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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->onDelete('set null');
            $table->integer('counter')->default(0);
            $table->integer('checkin')->default(0);
            $table->integer('checkout')->default(0);
            $table->boolean('confirmation')->default(0);
            $table->boolean('afterpublish')->nullable();
            $table->boolean('application')->nullable();
            $table->string('status')->default('disapproved');
            $table->bigInteger('payment_status')->nullable();
            $table->bigInteger('control_number')->nullable();
            $table->integer('block_id')->nullable();
            $table->integer('room_id')->nullable();
            $table->integer('floor_id')->nullable();
            $table->integer('bed_id')->nullable();
            $table->timestamp('expiration_date')->nullable();
            $table->boolean('verify')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
