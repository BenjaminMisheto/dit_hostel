<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSemestersTable extends Migration
{
    public function up()
    {
        Schema::create('semesters', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Semester name, e.g., '2023/2024'
            $table->date('start_date')->nullable(); // Start date of the semester (optional)
            $table->date('end_date')->nullable(); // End date of the semester (optional)
            $table->boolean('is_closed')->default(false); // Indicates if the semester is closed (default: not closed)
            $table->timestamps(); // Created_at and updated_at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('semesters'); // Rollback operation
    }
}
