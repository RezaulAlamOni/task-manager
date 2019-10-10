<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomeColumnsToTaskListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_lists', function (Blueprint $table) {
            $table->bigInteger('parent_id')->nullable()->change();
            $table->Integer('list_id')->nullable()->change();
            $table->bigInteger('board_parent_id')->after('parent_id')->nullable();
            $table->Integer('multiple_board_id')->after('list_id')->nullable();
            $table->Integer('task_flag')->after('multiple_board_id')->nullable();
            $table->Integer('board_flag')->after('task_flag')->nullable();
            $table->text('color')->after('title')->nullable();
            $table->text('progress')->after('color')->nullable();
            $table->bigInteger('hidden')->after('progress')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_lists', function (Blueprint $table) {
            $table->dropColumn('board_parent_id');
            $table->dropColumn('multiple_board_id');
            $table->dropColumn('task_flag');
            $table->dropColumn('board_flag');
            $table->dropColumn('color');
            $table->dropColumn('progress');
            $table->dropColumn('hidden');
        });
    }
}
