<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequirementItemConfirmationsTable extends Migration
{
    public function up()
    {
        Schema::create('requirement_item_confirmations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('semester_id'); // Add semester_id column
            $table->string('block_name')->nullable();
            $table->string('floor_name')->nullable();
            $table->string('room_name')->nullable();
            $table->string('bed_name')->nullable();
            $table->string('course_name')->nullable();
            $table->json('items_to_bring_names')->nullable();
            $table->json('checkout_items_names')->nullable();
            $table->timestamps();

            // Foreign key constraint for user_id
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            // Foreign key constraint for semester_id
            $table->foreign('semester_id')
                  ->references('id')
                  ->on('semesters')
                  ->onDelete('cascade');

            // Composite unique constraint on user_id and semester_id
            $table->unique(['user_id', 'semester_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('requirement_item_confirmations');
    }
}
