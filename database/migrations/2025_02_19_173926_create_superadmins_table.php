<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuperAdminsTable extends Migration
{
    public function up()
    {
        Schema::create('super_administrators', function (Blueprint $table) {
            $table->id('суперадминистратор_id');
            $table->string('имя');
            $table->string('логин')->unique();
            $table->string('пароль');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('super_administrators');
    }
}
