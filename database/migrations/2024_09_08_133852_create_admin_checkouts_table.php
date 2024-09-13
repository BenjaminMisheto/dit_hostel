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
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->onDelete('set null');
            $table->unsignedBigInteger('user_id'); // Add user_id field
            $table->string('name');
            $table->string('condition');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('admin_checkouts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('admin_checkouts');
    }
}
