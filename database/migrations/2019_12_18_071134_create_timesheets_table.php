<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimesheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'timesheets', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedInteger('project_id')->default(0);
            $table->unsignedInteger('user_id')->default(0);
            $table->unsignedInteger('task_id')->default(0);
            $table->date('date');
            $table->float('hours')->default(0.0);
            $table->text('remark')->nullable();
            $table->timestamps();
        }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timesheets');
    }
}
