<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedSmallInteger('sort')->default(0);
            $table->string('name', 100)->unique();
            $table->string('paddle_id', 100)->unique();
            $table->float('price')->default(0);
            $table->boolean('active')->default(1);
            $table->boolean('trial')->default(1);
            $table->boolean('deal')->default(0);
            $table->unsignedInteger('duration')->nullable();
            $table->unsignedInteger('max_clients')->nullable();
            $table->unsignedInteger('max_projects')->nullable();
            $table->unsignedInteger('max_users')->nullable();
            $table->unsignedInteger('max_space')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('subscription_plans');
    }
}
