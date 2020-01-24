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
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->unsignedInteger('assign_to')->nullable();
            $table->unsignedInteger('project_id')->nullable();
            $table->unsignedInteger('milestone_id')->nullable();
            $table->string('status')->default('todo');
            $table->unsignedInteger('stage')->default(0);
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
        Schema::dropIfExists('task');
    }
}
