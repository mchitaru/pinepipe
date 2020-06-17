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
            $table->unsignedBigInteger('calendar_id');
            $table->string('google_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamp('start')->default(now());
            $table->timestamp('end')->default(now());
            $table->boolean('allday')->default(false);
            $table->boolean('busy')->default(true);
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('user_id')->default(0);
            $table->unsignedInteger('created_by')->default(0);
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
        Schema::dropIfExists('events');
    }
}
