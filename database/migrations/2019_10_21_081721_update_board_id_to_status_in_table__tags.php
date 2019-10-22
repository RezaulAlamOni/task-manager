<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBoardIdToStatusInTableTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        if (Schema::hasColumn('tags', 'board_id'))
//        {
//            Schema::table('tags', function (Blueprint $table)
//            {
//                $table->dropColumn('board_id');
//                $table->dropColumn('task_id');
//
//            });
//
//        }
        Schema::table('tags', function (Blueprint $table) {
            if (Schema::hasColumn('tags', 'board_id'))
            {
                $table->dropColumn('board_id');
            }if (Schema::hasColumn('tags', 'task_id'))
            {
                    $table->dropColumn('task_id');
            }

            $table->tinyInteger( 'team_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tags', function (Blueprint $table) {
            //
        });
    }
}
