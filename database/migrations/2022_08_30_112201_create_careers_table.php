<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCareersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->string('position',100);
            $table->string('slug',255)->unique();
            $table->string('location',100)->nullable();
            $table->string('exp_range',50)->nullable();
            $table->string('salary_range',100)->nullable();
            $table->integer('no_of_position')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('departments')->onUpdate('SET NULL')->onDelete('SET NULL');
            $table->text('description')->nullable();
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
        Schema::dropIfExists('careers');
    }
}
