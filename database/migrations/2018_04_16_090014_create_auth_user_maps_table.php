<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthUserMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 用户 -- 菜单/节点 关联表
         */
        Schema::create('auth_user_maps', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0)->index()->comment('用户id');
            $table->tinyInteger('type')->default(0)->comment('类型 0菜单 1节点');
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
        Schema::dropIfExists('auth_user_maps');
    }
}
