<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_id')->unique()->after('id');
            $table->string('first_name')->nullable()->after('password');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('photo_url')->nullable()->after('last_name');
            $table->date('birth_date')->nullable()->after('photo_url');
            $table->string('country_code', 3)->nullable()->after('birth_date');
            $table->mediumText('metafield')->nullable()->after('country_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('photo_url');
            $table->dropColumn('birth_date');
            $table->dropColumn('country_code');
            $table->dropColumn('metafield');
        });
    }
}
