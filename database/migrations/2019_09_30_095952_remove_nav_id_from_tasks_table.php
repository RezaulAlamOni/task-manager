<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveNavIdFromTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('nav_id');
        });
        Schema::table('action_logs', function (Blueprint $table) {
            $table->dropColumn('nav_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('nav_id');
        });
        Schema::table('action_logs', function (Blueprint $table) {
            $table->dropColumn('nav_id');
        });
    }
}
