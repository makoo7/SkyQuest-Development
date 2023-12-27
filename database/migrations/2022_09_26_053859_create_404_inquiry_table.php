<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create404InquiryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('404_inquiry', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->string('email',255)->nullable();
            $table->foreignId('country_id')->nullable()->constrained('countries')->onUpdate('SET NULL')->onDelete('SET NULL');
            $table->string('phone',20)->nullable();
            $table->string('company_name',255)->nullable();
            $table->string('designation',255)->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('404_inquiry');
    }
}
