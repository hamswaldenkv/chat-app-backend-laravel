<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('event_id')->unique();
            $table->string('title');
            $table->string('description', 300);
            $table->string('photo_url', 400)->nullable();
            $table->string('organisator_name');
            $table->string('venue_place');
            $table->string('venue_address', 300)->nullable();
            $table->double('venue_location_latitude')->nullable();
            $table->double('venue_location_longitude')->nullable();
            $table->tinyInteger('is_live');
            $table->tinyInteger('is_free');
            $table->double('price_value')->nullable();
            $table->string('price_currency')->nullable();
            $table->mediumText('metafield')->nullable();
            $table->integer('count_participants')->default(0);
            $table->integer('status');
            $table->integer('start_at');
            $table->integer('finish_at');
            $table->softDeletes();
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
        Schema::dropIfExists('events');
    }
}
