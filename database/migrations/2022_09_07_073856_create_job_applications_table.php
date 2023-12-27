<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();            
            $table->foreignId('career_id')->nullable()->constrained('careers')->onUpdate('SET NULL')->onDelete('SET NULL');
            $table->string('first_name',255);
            $table->string('last_name',255)->nullable();
            $table->string('email',255)->nullable();
            $table->string('phone',20)->nullable();
            $table->string('work_experience',100)->nullable();
            $table->string('notice_period',100)->nullable();
            $table->string('current_ctc',100)->nullable();
            $table->string('expected_ctc',100)->nullable();
            $table->string('resume',255)->nullable();
            $table->string('portfolio_or_web',100)->nullable();
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
        Schema::dropIfExists('job_applications');
    }
}
