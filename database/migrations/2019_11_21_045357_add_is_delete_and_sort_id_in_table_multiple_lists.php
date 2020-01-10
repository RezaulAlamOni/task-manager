<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsDeleteAndSortIdInTableMultipleLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('multiple_lists', function (Blueprint $table) {
            $table->tinyInteger('sort_id')->default(0)->after('project_id');
            $table->tinyInteger('is_delete')->default(0)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('multiple_lists', function (Blueprint $table) {
            $table->dropColumn('sort_id');
            $table->dropColumn('is_delete');
        });
    }
}
