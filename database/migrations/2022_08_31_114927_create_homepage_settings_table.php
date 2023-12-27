<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomepageSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homepage_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_case_study')->default(1);
            $table->boolean('is_feedback')->default(1);
            $table->boolean('is_help')->default(1);
            $table->boolean('is_insights')->default(1);
            $table->boolean('is_process')->default(1);
            $table->boolean('is_products')->default(1);
            $table->boolean('is_awards')->default(1);
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
        Schema::dropIfExists('homepage_settings');
    }
}
