<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsToTaskBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_boards', function (Blueprint $table) {
            $table->tinyInteger('hidden')->after('multiple_board_id');
            $table->tinyInteger('progress')->nullable()->after('hidden');
            $table->string('color')->nullable()->after('progress');
            $table->text('tags')->nullable()->after('color');
            $table->date('date')->nullable()->after('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_boards', function (Blueprint $table) {
            $table->dropColumn('hidden');
            $table->dropColumn('progress');
            $table->dropColumn('color');
            $table->dropColumn('tags');
            $table->dropColumn('date');
        });
    }
}
