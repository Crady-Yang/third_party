<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthMenuNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 菜单 -- 节点 关联表
         */
        Schema::create('auth_menu_nodes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_id')->index()->comment('菜单id');
            $table->integer('node_id')->comment('节点id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auth_menu_nodes');
    }
}
