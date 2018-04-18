<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 用户 -- 角色 关联表
         */
        Schema::create('auth_user_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0)->index()->comment('用户id');
            $table->integer('role_id')->default(0)->index()->comment('角色id');
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
        Schema::dropIfExists('auth_user_roles');
    }
}
