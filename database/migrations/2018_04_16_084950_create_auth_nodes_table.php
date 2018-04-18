<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * api节点表
         */
        Schema::create('auth_nodes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->comment('节点名');
            $table->string('desc', 200)->comment('节点描述');
            $table->string('url')->comment('api对应url');
            $table->string('key', 50)->unique()->index()->comment('唯一key');
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
        Schema::dropIfExists('auth_nodes');
    }
}
