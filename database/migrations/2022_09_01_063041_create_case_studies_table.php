<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseStudiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_studies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onUpdate('SET NULL')->onDelete('SET NULL');
            $table->string('name',255);
            $table->string('image',255)->nullable();
            $table->string('image_alt',255)->nullable();
            $table->string('read_time',20)->nullable();
            $table->string('location',255)->nullable();
            $table->foreignId('sectors_id')->nullable()->constrained('sectors')->onUpdate('SET NULL')->onDelete('SET NULL');
            $table->foreignId('service_id')->nullable()->constrained('services')->onUpdate('SET NULL')->onDelete('SET NULL');
            $table->string('slug',255)->unique();
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('schema')->nullable();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
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
        Schema::dropIfExists('case_studies');
    }
}
