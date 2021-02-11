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

            $table->bigInteger('votes');

            $table->binary('data');
            $table->boolean('confirmed');
            $table->char('name', 100);
            $table->date('date_at');
            $table->dateTime('dateTime_created_at', 0);
            $table->decimal('decimal_amount', 8, 2);
            $table->double('double_amount', 8, 2);
            $table->enum('level', ['easy', 'hard']);
            $table->float('float_amount', 8, 2);

            $table->integer('integer_votes');
            $table->ipAddress('visitor');
            $table->json('options');
            $table->jsonb('jsonb_options');
            $table->lineString('positions');
            $table->longText('description');
            $table->macAddress('device');
            $table->mediumInteger('mediumInteger_votes');
            $table->mediumText('mediumText_description');



//            $table->set('flavors', ['strawberry', 'vanilla']);
//            $table->smallIncrements('smallIncrements_id');
            $table->smallInteger('smallInteger_votes');
            $table->string('string_name', 100);
            $table->text('text_description');
            $table->time('time_sunrise', 0);
            $table->timeTz('timeTz_sunrise', 0);
            $table->timestampTz('added_on', 0);

//            $table->tinyIncrements('tinyIncrements_id');
            $table->tinyInteger('tinyIntegervotes');
            $table->unsignedBigInteger('unsignedBigInteger_votes');
            $table->unsignedDecimal('amount', 8, 2);
            $table->unsignedInteger('unsignedIntegervotes');
            $table->unsignedMediumInteger('unsignedMediumInteger_votes');
            $table->unsignedSmallInteger('unsignedSmallInteger_votes');
            $table->unsignedTinyInteger('unsignedTinyInteger_votes');
            $table->uuid('uuid');
            $table->year('birth_year');

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
