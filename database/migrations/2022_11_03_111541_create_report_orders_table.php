<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onUpdate('SET NULL')->onDelete('SET NULL');
            $table->foreignId('report_id')->references('id')->on('reports')->onDelete('cascade');
            $table->string('report_type',50)->nullable();
            $table->string('license_type',50)->nullable();
            $table->string('file_type',50)->nullable();
            $table->string('payment_method',50)->nullable();
            $table->string('payment_status',50)->nullable();
            $table->string('payment_id',255)->nullable();
            $table->decimal('price',11,2)->nullable();
            $table->string('name',255);
            $table->string('email',255)->nullable();
            $table->string('phone',20)->nullable();
            $table->string('company_name',255)->nullable();
            $table->string('designation',100)->nullable();
            $table->foreignId('country_id')->nullable()->constrained('countries')->onUpdate('SET NULL')->onDelete('SET NULL');
            $table->text('message')->nullable();
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
        Schema::dropIfExists('report_orders');
    }
}
