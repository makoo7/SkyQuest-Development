<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUniquekeyInsights extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insights', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->unique(['slug','deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('insights', function (Blueprint $table) {
            $table->dropUnique(['slug','deleted_at']);
            $table->unique(['slug']);
        });
    }
}
