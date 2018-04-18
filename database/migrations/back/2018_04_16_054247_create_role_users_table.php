<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->comment('角色id');
            $table->integer('user_id')->comment('用户id');
        });

//            $table->foreign('user_id')->references('id')->on('users');
//            $table->foreign('role_id')->references('id')->on('roles');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_users');
    }
}
