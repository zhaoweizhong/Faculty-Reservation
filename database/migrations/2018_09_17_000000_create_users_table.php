<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('sid')->unique();
            $table->string('password');
            $table->string('name');
            $table->string('email');
            $table->string('avatar_url')->nullable();
            $table->string('type');
            $table->text('intro')->nullable();
            $table->boolean('is_admin')->default(0);
            $table->rememberToken();
            $table->timestamps();
            //教师
            $table->string('office')->nullable();
            $table->string('fields')->nullable();
            $table->text('available_time')->nullable();
            //学生
            $table->string('gpa')->nullable();
            $table->string('interested_fields')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
