<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->integer('sender_id')->index();
            $table->integer('receiver_id')->index();
            $table->text('content');
            $table->boolean('read')->default(false);
            $table->integer('reply_id')->index()->nullable();//可选，这一条的所有回复的id数组
            $table->integer('reply_src_id')->index()->nullable();//可选，若此条为回复，则为目标id
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
        Schema::dropIfExists('messages');
    }
}
