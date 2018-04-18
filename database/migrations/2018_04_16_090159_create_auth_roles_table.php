<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 角色表
         */
        Schema::create('auth_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',50)->nullable()->default('')->comment('角色名');
            $table->string('desc',200)->nullable()->default('')->comment('角色描述');
            $table->tinyInteger('is_enable')->default(1)->comment('是否可用 0不可用 1可用');
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
        Schema::dropIfExists('auth_roles');
    }
}
