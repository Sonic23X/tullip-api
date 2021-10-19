<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContabilidadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contabilidad', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('cliente_id');
            $table->unsignedInteger('inmueble_id')->nulleable()->default(null);
            $table->dateTime('fecha_pago')->nullable()->default(null);
            $table->unsignedInteger('tipo');
            $table->float('monto', 10, 2);
            $table->unsignedInteger('empresa_id');
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
        Schema::dropIfExists('contabilidad');
    }
}
