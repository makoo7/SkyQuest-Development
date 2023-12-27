<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportInquiryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_inquiry', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->references('id')->on('reports')->onDelete('cascade');
            $table->string('name',255);
            $table->string('email',255)->nullable();
            $table->string('phone',20)->nullable();
            $table->string('company_name',255)->nullable();
            $table->string('designation',100)->nullable();
            $table->foreignId('country_id')->nullable()->constrained('countries')->onUpdate('SET NULL')->onDelete('SET NULL');
            $table->text('message')->nullable();
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
        Schema::dropIfExists('report_inquiry');
    }
}
