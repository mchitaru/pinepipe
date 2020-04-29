<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('active');
            $table->string('name');
            $table->timestamp('start')->default(now());
            $table->timestamp('end')->default(now());
            $table->boolean('busy');
            $table->text('notes')->nullable();
            $table->unsignedInteger('user_id')->default(0);
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
        Schema::dropIfExists('events');
    }
}
