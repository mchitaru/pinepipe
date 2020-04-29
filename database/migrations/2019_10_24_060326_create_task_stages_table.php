<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'task_stages', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('color', 15)->nullable();
            $table->unsignedInteger('created_by')->default(0);
            $table->unsignedInteger('order')->default(0);;
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
        Schema::dropIfExists('task_stages');
    }
}
