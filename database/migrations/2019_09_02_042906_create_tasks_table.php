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
            $table->bigInteger('sort_id')->default(0);
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->unsignedInteger('project_id');
            $table->integer('list_id');
            $table->bigInteger('nav_id');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->text('title');
            $table->string('tag');
            $table->date('date');
            $table->timestamps();


            $table->foreign('project_id')->references('id')->on('projects');
//            $table->foreign('list_id')->references('id')->on('multiple_lists');
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
