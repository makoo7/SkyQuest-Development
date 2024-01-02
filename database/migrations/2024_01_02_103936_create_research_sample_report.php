<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResearchSampleReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('research_sample_report', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sales_report_id');
            $table->bigInteger('report_id');
            $table->longText('message');
            $table->bigInteger('from_id');
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
        Schema::dropIfExists('research_sample_report');
    }
}
