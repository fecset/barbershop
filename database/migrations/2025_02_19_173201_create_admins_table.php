<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    public function up()
    {
        Schema::create('administrators', function (Blueprint $table) {
            $table->id('администратор_id');
            $table->string('имя');
            $table->string('логин')->unique();
            $table->string('пароль');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('administrators');
    }
}


