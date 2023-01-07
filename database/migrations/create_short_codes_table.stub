<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShortCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('short_codes', function (Blueprint $table) {
            $table->id();
            $table->string('environment', 100)->default('sandbox');
            $table->enum('direction', ['c2b', 'b2c']);
            $table->string('shortcode', 100)->unique();
            $table->longText('consumer_key');
            $table->longText('consumer_secret');
            $table->longText('pass_key')->nullable();
            $table->string('initiator_name', 100)->nullable();
            $table->string('initiator_password', 100)->nullable();
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
        Schema::dropIfExists('short_codes');
    }
};