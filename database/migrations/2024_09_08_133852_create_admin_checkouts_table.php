<?php

// database/migrations/xxxx_xx_xx_create_admin_checkouts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminCheckoutsTable extends Migration
{
    public function up()
    {
        Schema::create('admin_checkouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Add user_id field
            $table->unsignedBigInteger('semester_id'); // Add semester_id column
            $table->string('block_name')->nullable();
            $table->string('floor_name')->nullable();
            $table->string('room_name')->nullable();
            $table->string('bed_name')->nullable();
            $table->string('course_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('name');
            $table->string('condition');
            $table->decimal('payment_price', 10, 2)->nullable(); // Add payment price field
            $table->string('control_number')->nullable(); // Add control number field with uniqueness constraint
            $table->boolean('paid')->default(false); // Add the "paid" column to track payment status
            $table->timestamp('payment_date')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            // Foreign key constraint for semester_id
            $table->foreign('semester_id')
                  ->references('id')
                  ->on('semesters')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('admin_checkouts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['semester_id']);
        });

        Schema::dropIfExists('admin_checkouts');
    }
}
