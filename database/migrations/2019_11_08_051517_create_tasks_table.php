<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('priority');
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->date('start_date')->nullable();
            $table->integer('assign_to')->nullable();
            $table->integer('project_id')->nullable();
            $table->integer('milestone_id')->nullable();
            $table->string('status')->default('todo');
            $table->integer('stage')->default(0);
            $table->integer('order')->default(0);
            $table->integer('created_by')->default('0');
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
        Schema::dropIfExists('task');
    }
}
