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
            $table->float('price')->nullable();
            $table->unsignedInteger('stage_id')->default(0);
            $table->unsignedInteger('order')->default(0);
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('client_id')->nullable();
            $table->unsignedInteger('contact_id')->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->boolean('archived')->default(false);
            $table->unsignedInteger('created_by')->default(0);
            $table->timestamps();
            $table->softDeletes();            
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
