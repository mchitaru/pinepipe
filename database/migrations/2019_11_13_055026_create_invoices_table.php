<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('increment');
            $table->string('number')->nullable();
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('project_id')->nullable();
            $table->string('status');
            $table->date('issue_date');
            $table->date('due_date');
            $table->float('discount');
            $table->unsignedInteger('tax_id')->nullable();
            $table->string('currency', 3)->nullable();
            $table->float('rate')->nullable();
            $table->string('locale', 10)->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('created_by');
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
        Schema::dropIfExists('invoices');
    }
}
