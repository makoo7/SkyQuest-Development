<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastnameToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('lastname')->nullable();
        });

        Schema::table('report_inquiry', function (Blueprint $table) {
            $table->string('lastname')->nullable();
        });

        Schema::table('report_sample_request', function (Blueprint $table) {
            $table->string('lastname')->nullable();
        });

        Schema::table('report_subscribe_now', function (Blueprint $table) {
            $table->string('lastname')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('lastname');
        });

        Schema::table('report_inquiry', function (Blueprint $table) {
            $table->dropColumn('lastname');
        });

        Schema::table('report_sample_request', function (Blueprint $table) {
            $table->dropColumn('lastname');
        });

        Schema::table('report_subscribe_now', function (Blueprint $table) {
            $table->dropColumn('lastname');
        });
    }
}
