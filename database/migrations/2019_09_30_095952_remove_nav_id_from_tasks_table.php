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
        if (Schema::hasColumn('task_lists', 'nav_id'))
        {
            Schema::table('task_lists', function (Blueprint $table) {
                $table->dropColumn('nav_id');
            });
        }
        if (Schema::hasColumn('action_logs', 'nav_id'))
        {
            Schema::table('action_logs', function (Blueprint $table) {
                $table->dropColumn('nav_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_lists', function (Blueprint $table) {
            $table->dropColumn('nav_id');
        });
        Schema::table('action_logs', function (Blueprint $table) {
            $table->dropColumn('nav_id');
        });
    }
}
