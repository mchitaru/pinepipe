<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('type', 20);
            $table->string('avatar')->nullable();
            $table->string('lang', 100);
            $table->text('bio')->nullable();
            $table->unsignedInteger('created_by')->default(0);
            $table->unsignedInteger('client_id')->nullable();
            $table->boolean('notify_task_assign')->default(true);
            $table->boolean('notify_project_assign')->default(true);
            $table->boolean('notify_project_activity')->default(false);
            $table->boolean('notify_item_overdue')->default(true);
            $table->boolean('notify_newsletter')->default(false);
            $table->boolean('notify_major_updates')->default(true);
            $table->boolean('notify_minor_updates')->default(false);
            $table->boolean('enabled')->default(true);
            $table->timestamp('trial_ends_at')->nullable();            
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
