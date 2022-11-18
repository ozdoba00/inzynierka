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
           $table->string('name')->nullable();
           $table->timestamp('starts');
           $table->timestamp('ends')->nullable();
           $table->string('status');
           $table->text('summary');
           $table->string('location')->nullable();
           $table->string('uid')->nullable();
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
        Schema::dropIfExists('calendar');
    }
}
