<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLimitsToSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->unsignedInteger('max_clients')->nullable();
            $table->unsignedInteger('max_projects')->nullable();
            $table->unsignedInteger('max_users')->nullable();
            $table->unsignedInteger('max_space')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('max_clients');
            $table->dropColumn('max_projects');
            $table->dropColumn('max_users');
            $table->dropColumn('max_space');
        });
    }
}
