<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('запись_id');

            // Указываем тип поля и явно связываем его с колонкой 'клиент_id' в таблице 'clients'
            $table->unsignedBigInteger('клиент_id');
            $table->unsignedBigInteger('мастер_id');
            $table->unsignedBigInteger('услуга_id');

            $table->dateTime('дата_время');
            $table->string('статус');
            $table->timestamps();

            // Внешний ключ для клиента:
            $table->foreign('клиент_id')
                ->references('клиент_id') // Ссылаемся на 'клиент_id' в таблице 'clients'
                ->on('clients')
                ->onDelete('cascade');

            // Для мастера и услуги можно сделать аналогично:
            $table->foreign('мастер_id')
                ->references('мастер_id')
                ->on('masters')
                ->onDelete('cascade');

            $table->foreign('услуга_id')
                ->references('услуга_id')
                ->on('services')
                ->onDelete('cascade');
        });

    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
