<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMultipleListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('multiple_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('project_id');
            $table->bigInteger('nav_id');
            $table->string('list_title');
            $table->text('description')->nullable();
            $table->timestamps();

//            $table->foreign('project_id')->references('id')->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('multiple_lists');
    }
}
