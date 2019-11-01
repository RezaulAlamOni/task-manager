<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->default(0)->nullable();
            $table->unsignedInteger('project_id');
            $table->bigInteger('board_parent_id')->nullable();
            $table->integer('list_id')->nullable();
            $table->Integer('multiple_board_id')->nullable();
            $table->Integer('task_flag')->nullable();
            $table->Integer('board_flag')->nullable();
            $table->bigInteger('hidden')->nullable();
            $table->bigInteger('sort_id')->default(0);
            $table->Biginteger('board_sort_id')->default(0);//
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->tinyInteger('open')->default(1);//
            $table->text('title');
            $table->text('description')->nullable('');//
            $table->text('color')->nullable();
            $table->text('progress')->nullable();
            $table->date('date');
            $table->tinyInteger('is_complete')->default(0);
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_lists');
    }
}
