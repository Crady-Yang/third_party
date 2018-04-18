<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 菜单表
         */
        Schema::create('auth_menus', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('app_id')->default(0)->comment('不同应用 0招聘crm 1知识库');
            $table->string('name', 50)->nullable()->default('')->comment('菜单名');
            $table->string('desc', 200)->nullable()->default('')->comment('菜单描述');
            $table->integer('parent_id')->default(0)->comment('上级菜单id');
            $table->smallInteger('sort')->default(0)->comment('排序 越大越后');
            $table->smallInteger('level')->default(0)->commment('菜单层级');
            $table->tinyInteger('is_show')->default(0)->comment('是否展示');
            $table->string('key', 50)->index()->comment('菜单key');
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
        Schema::dropIfExists('auth_menus');
    }
}
