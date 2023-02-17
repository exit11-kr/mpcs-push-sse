<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePushSseUuidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_sse_uuids', static function (Blueprint $table) {
            $table->increments('id');
            $table->integer('push_sse_id')->unsigned()->comment("push_sse.id");
            $table->string('uuid', 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('push_sse_uuids');
    }
}
