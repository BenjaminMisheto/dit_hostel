<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewCountsTable extends Migration
{
    public function up()
    {
        Schema::create('view_counts', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('total_views')->default(0);
            $table->unsignedInteger('monthly_views')->default(0);
            $table->unsignedInteger('views_january')->default(0);
            $table->unsignedInteger('views_february')->default(0);
            $table->unsignedInteger('views_march')->default(0);
            $table->unsignedInteger('views_april')->default(0);
            $table->unsignedInteger('views_may')->default(0);
            $table->unsignedInteger('views_june')->default(0);
            $table->unsignedInteger('views_july')->default(0);
            $table->unsignedInteger('views_august')->default(0);
            $table->unsignedInteger('views_september')->default(0);
            $table->unsignedInteger('views_october')->default(0);
            $table->unsignedInteger('views_november')->default(0);
            $table->unsignedInteger('views_december')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('view_counts');
    }
}
