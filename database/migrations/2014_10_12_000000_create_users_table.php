<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(! Schema::hasTable('users')){
            Schema::create('users', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->tinyInteger('role_id')->default(2)->comment('1: admin, 2: user');
                $table->string('name')->nullable();
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('gender')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->unique()->nullable();
                $table->string('country_code')->nullable();
                $table->string('profile_image')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password')->nullable(); 
                $table->tinyInteger('status')->default(1)->comment('1: active, 0: inactive');
                $table->softDeletes();
                $table->rememberToken();
                $table->timestamps();
            });
        }
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
