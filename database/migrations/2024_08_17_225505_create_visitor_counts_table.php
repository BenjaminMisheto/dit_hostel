<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorCountsTable extends Migration
{
    public function up()
    {
        Schema::create('visitor_counts', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('total_visitors')->default(0);
            $table->unsignedInteger('new_visitors')->default(0);
            // Columns for each month's visitor data
            $table->unsignedInteger('visitors_january')->default(0);
            $table->unsignedInteger('visitors_february')->default(0);
            $table->unsignedInteger('visitors_march')->default(0);
            $table->unsignedInteger('visitors_april')->default(0);
            $table->unsignedInteger('visitors_may')->default(0);
            $table->unsignedInteger('visitors_june')->default(0);
            $table->unsignedInteger('visitors_july')->default(0);
            $table->unsignedInteger('visitors_august')->default(0);
            $table->unsignedInteger('visitors_september')->default(0);
            $table->unsignedInteger('visitors_october')->default(0);
            $table->unsignedInteger('visitors_november')->default(0);
            $table->unsignedInteger('visitors_december')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('visitor_counts');
    }
}
