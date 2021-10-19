<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClienteInmuebleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cliente_inmueble', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('cliente_id');
            $table->unsignedInteger('inmueble_id');
            $table->unsignedInteger('empresa_id');
            $table->string('tipo');
            $table->string('numero_recibo', 15);
            $table->string('referencia', 15);
            $table->dateTime('fecha');
            $table->decimal('monto', 10, 2);
            $table->decimal('monto_total', 10, 2);
            $table->string('comentario');
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
        Schema::dropIfExists('cliente_inmueble');
    }
}
