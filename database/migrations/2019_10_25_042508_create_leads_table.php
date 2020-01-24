<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'leads', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->string('name');
            $table->float('price')->default(0);
            $table->unsignedInteger('stage')->default(0);
            $table->unsignedInteger('owner')->default(0);
            $table->unsignedInteger('client')->default(0);
            $table->unsignedInteger('source')->default(0);
            $table->unsignedInteger('created_by')->default(0);
            $table->text('notes');
            $table->unsignedInteger('item_order')->default(0);
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
        Schema::dropIfExists('leads');
    }
}
