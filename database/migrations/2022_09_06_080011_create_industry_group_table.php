<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndustryGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('industry_group', function (Blueprint $table) {
            $table->id();
            $table->string('title',255);
            $table->string('code',20)->nullable();
            $table->string('slug',255)->unique();           
            $table->foreignId('sector_id')->nullable()->constrained('sector')->onUpdate('SET NULL')->onDelete('SET NULL');
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('industry_group');
    }
}
