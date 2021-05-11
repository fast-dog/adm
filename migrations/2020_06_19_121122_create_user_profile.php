<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profile', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique('UNX_USER_ID');
            $table->unsignedBigInteger('company_id')->nullable();

            $table->date('birthday')->nullable();
            $table->string('website')->nullable();

            $table->foreign('user_id')
                ->onDelete('cascade')
                ->references('id')
                ->on('users');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profile');
    }
}
