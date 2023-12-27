<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_type')->nullable();
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('sector_id')->references('id')->on('sector')->onDelete('cascade');
            $table->foreignId('industry_group_id')->references('id')->on('industry_group')->onDelete('cascade');
            $table->foreignId('industry_id')->references('id')->on('industry')->onDelete('cascade');
            $table->foreignId('sub_industry_id')->references('id')->on('sub_industry')->onDelete('cascade');
            $table->string('country')->nullable();
            $table->string('report_data')->nullable();
            $table->string('product_id')->nullable();
            $table->string('download')->nullable();
            $table->string('image_alt')->nullable();
            $table->string('slug')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('pages')->nullable();
            $table->text('parent_market')->nullable();
            $table->text('methodologies')->nullable();
            $table->text('analyst_support')->nullable();
            $table->text('market_insights')->nullable();
            $table->text('segmental_analysis')->nullable();
            $table->text('regional_insights')->nullable();
            $table->text('market_dynamics')->nullable();
            $table->text('competitive_landscape')->nullable();
            $table->text('key_market_trends')->nullable();
            $table->text('skyQuest_analysis')->nullable();
            $table->text('whats_included')->nullable();
            $table->timestamp('publish_date')->nullable();
            $table->text('s_c')->nullable();
            $table->text('free_sample_report_link')->nullable();
            $table->boolean('is_active')->default(1);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('reports');
    }
}
