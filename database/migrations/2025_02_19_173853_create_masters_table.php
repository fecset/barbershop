<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMastersTable extends Migration
{
    public function up()
    {
        Schema::create('masters', function (Blueprint $table) {
            $table->id('мастер_id');
            $table->string('имя');
            $table->string('специализация');
            $table->string('график_работы');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('masters');
    }
}
