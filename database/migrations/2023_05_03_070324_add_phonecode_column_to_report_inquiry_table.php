<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhonecodeColumnToReportInquiryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('report_inquiry', function (Blueprint $table) {
            $table->string('phonecode',10)->nullable()->after('email');
            $table->string('linkedin_link')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('report_inquiry', function (Blueprint $table) {
            $table->dropColumn(['phonecode', 'linkedin_link']);
        });
    }
}
