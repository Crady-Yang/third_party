<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthRoleMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 角色 -- 菜单/节点 关联表
         */
        Schema::create('auth_role_maps', function (Blueprint $table) {
            $table->increments('id');
//            $table->tinyInteger('select_type')->default(0)->comment('筛选类型 0用户菜单节点关系 1角色菜单节点关系');
            $table->integer('role_id')->default(0)->index()->comment('角色id');
            $table->tinyInteger('type')->default(0)->comment('扩展id类型 0菜单 1节点');
            $table->integer('ext_id')->default(0)->comment('扩展id');
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
        Schema::dropIfExists('auth_role_maps');
    }
}
