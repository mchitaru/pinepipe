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
            $table->unsignedInteger('user_id')->default(0);
            $table->unsignedInteger('project_id')->nullable();
            $table->unsignedInteger('task_id')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->date('date');
            $table->unsignedInteger('hours')->default(0);
            $table->unsignedInteger('minutes')->default(0);
            $table->unsignedInteger('seconds')->default(0);
            $table->float('rate')->nullable();
            $table->text('remark')->nullable();
            $table->unsignedInteger('created_by')->default(0);
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
