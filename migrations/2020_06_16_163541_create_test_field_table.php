<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestFieldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_field', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('votes')->nullable();

            $table->binary('data')->nullable();
            $table->boolean('confirmed')->nullable();
            $table->char('name', 100);
            $table->date('date_at')->nullable();
            $table->dateTime('dateTime_created_at', 0)->nullable();
            $table->decimal('decimal_amount', 8, 2)->nullable();
            $table->double('double_amount', 8, 2)->nullable();
            $table->enum('level', ['easy', 'hard'])->nullable();
            $table->float('float_amount', 8, 2)->nullable();
            $table->integer('integer_votes')->nullable();
            $table->ipAddress('visitor')->nullable();
            $table->json('options')->nullable();
            $table->jsonb('jsonb_options')->nullable();
//            $table->lineString('positions');
            $table->longText('description')->nullable();
            $table->macAddress('device')->nullable();
            $table->mediumInteger('mediumInteger_votes')->nullable();
            $table->mediumText('mediumText_description')->nullable();
//            $table->set('flavors', ['strawberry', 'vanilla']);
//            $table->smallIncrements('smallIncrements_id');
            $table->smallInteger('smallInteger_votes')->nullable();
            $table->string('string_name', 100)->nullable();
            $table->text('text_description')->nullable();
            $table->time('time_sunrise', 0)->nullable();
            $table->timeTz('timeTz_sunrise', 0)->nullable();
            $table->timestampTz('added_on', 0)->nullable();
//            $table->tinyIncrements('tinyIncrements_id');
            $table->tinyInteger('tinyIntegerVotes')->nullable();
            $table->unsignedBigInteger('unsignedBigInteger_votes')->nullable();
            $table->unsignedDecimal('amount', 8, 2)->nullable();
            $table->unsignedInteger('unsignedIntegerVotes')->nullable();
            $table->unsignedMediumInteger('unsignedMediumInteger_votes')->nullable();
            $table->unsignedSmallInteger('unsignedSmallInteger_votes')->nullable();
            $table->unsignedTinyInteger('unsignedTinyInteger_votes')->nullable();
            $table->uuid('uuid')->nullable();
            $table->year('birth_year')->nullable();

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
        Schema::dropIfExists('test_field');
    }
}
