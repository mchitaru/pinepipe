<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('amount')->nullable();
            $table->date('date')->nullable();
            $table->unsignedInteger('project_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->text('description')->nullable();
            $table->string('attachment')->nullable();
            $table->unsignedInteger('created_by')->default('0');
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
        Schema::dropIfExists('expenses');
    }
}
