<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsPostventaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets_postventa', function (Blueprint $table) {
            $table->id();
            $table->date('fecha')->nullable();
            $table->integer('id_inmueble')->unsignable();
            $table->json('detalles');
            $table->string('reporte_url')->nullable();
            $table->string('valoracion')->nullable();
            $table->string('comentarios')->nullable();
            $table->string('observaciones')->nullable();
            $table->date('fecha_conclusion')->nullable();
            $table->string('status')->default('abierto');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets_postventa');
    }
}
