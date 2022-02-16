<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePushSsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_sses', static function (Blueprint $table) {
            $table->increments('id');
            $table->string('event', 50)->comment("이벤트명");
            $table->string('title', 100)->nullable();
            $table->string('message');
            $table->json('params')->nullable();
            $table->string('variant', 50)->nullable();
            $table->string('url')->nullable();
            $table->boolean('is_private')->default(0)->comment("0: public, 1: private");
            $table->boolean('notification')->default(0)->comment("0: no, 1: yes");
            $table->timestamp('pushed_at')->nullable()->comment("발송시간");
            $table->boolean('delivered')->default(0)->comment("0: no, 1: yes");
            $table->integer('push_check_id')->unsigned()->nullable();
            $table->string('client', 50)->nullable();
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
        Schema::drop('push_sses');
    }
}
