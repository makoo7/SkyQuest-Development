<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_feedback', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->string('image',255)->nullable();
            $table->string('company_name',255)->nullable();
            $table->string('designation',100)->nullable();
            $table->text('feedback')->nullable();
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('client_feedback');
    }
}
