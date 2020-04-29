<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug');
            $table->string('class');
            $table->boolean('active');
            $table->unsignedInteger('order')->default(0);
            $table->string('description')->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('created_by')->default(0);
            $table->timestamps();
            $table->softDeletes();            
        });

        Schema::create('categorizables', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('category_id')->default(0);
            $table->morphs('categorizable');
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
        Schema::dropIfExists('categorizables');
        Schema::dropIfExists('categories');
    }
}
