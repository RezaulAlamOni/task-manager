<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('action_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('project_id')->nullable();
            $table->bigInteger('multiple_list_id')->nullable();
            $table->bigInteger('task_id')->nullable();
            $table->bigInteger('multiple_board_id')->nullable();
            $table->bigInteger('board_id')->nullable();
            $table->text('title')->nullable();
            $table->string('log_type')->nullable();
            $table->string('action_type')->nullable();
            $table->dateTime('action_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('action_logs');
    }
}
