<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlgeriaCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('algeria_cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('wilaya_code');
            $table->string('wilaya_name');
            $table->string('wilaya_name_ar')->nullable();
            $table->string('daira_code');
            $table->string('daira_name');
            $table->string('daira_name_ar')->nullable();
            $table->string('commune_code');
            $table->string('commune_name');
            $table->string('commune_name_ar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('algeria_cities');
    }
}
