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
            $table->unsignedInteger('invoice_id');
            $table->unsignedInteger('project_id');
            $table->string('status');
            $table->date('issue_date');
            $table->date('due_date');
            $table->float('discount');
            $table->unsignedInteger('tax_id')->nullable();
            $table->text('notes')->nullable();
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
