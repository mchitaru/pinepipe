<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_stages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('color',25)->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->unsignedInteger('created_by')->default(0);
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
        Schema::dropIfExists('lead_stages');
    }
}
