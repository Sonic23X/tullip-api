<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketPostventaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets_postventa', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha')->nullable();
            $table->integer('id_inmueble')->unsignable();
            $table->json('detalles');
            $table->string('reporte_url')->nullable();
            $table->enum('valoracion',['procedente','no procedente'])
                        ->nullable();
            $table->string('observaciones')->nullable();
            $table->date('fecha_conclusion')->nullable();
            $table->string('estatus')->default('abierto');
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
        Schema::dropIfExists('ticket_postventa');
    }
}
