<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'projects', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->float('price')->default(0.00);
            $table->date('start_date');
            $table->date('due_date');
            $table->unsignedInteger('client_id')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('lead_id')->nullable();
            $table->boolean('enabled')->default(true);
            $table->boolean('archived')->default(false);
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
        Schema::dropIfExists('projects');
    }
}
