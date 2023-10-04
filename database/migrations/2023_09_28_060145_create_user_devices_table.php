<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->string('user_device_id')->unique();
            $table->integer('user_id')->index();
            $table->string('name');
            $table->string('unique_id');
            $table->string('device_id')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('platform')->nullable();
            $table->string('os_version')->nullable();
            $table->mediumText('firebase_token')->nullable();
            $table->integer('status');
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
        Schema::dropIfExists('user_devices');
    }
}
