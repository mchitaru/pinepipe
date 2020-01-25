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
            $table->unsignedInteger('stage_id')->default(0);
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('client_id')->nullable();
            $table->unsignedInteger('contact_id')->nullable();
            $table->unsignedInteger('source_id')->default(0);
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
