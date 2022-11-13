<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar', function (Blueprint $table) {
           $table->id();
           $table->unsignedBigInteger("user_id");
           $table->string('name');
           $table->timestamp('starts');
           $table->timestamp('ends')->nullable();
           $table->string('status');
           $table->text('summary');
           $table->string('location');
           $table->string('uid');
           $table->timestamps();
           $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar');
    }
}
