<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_tableofcontent', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->references('id')->on('reports')->onDelete('cascade');
            $table->longtext('toc')->nullable();
            $table->longtext('tables')->nullable();
            $table->longtext('figures')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_tableofcontent');
    }
};
