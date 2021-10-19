<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInmueblesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inmuebles', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('prototipo_id');
            $table->string('manzana', 4)->nullable()->default(null);
            $table->string('lote')->nullable()->default(null);
            $table->string('calle', 150)->nullable()->default(null);
            $table->smallInteger('numero')->nullable()->default(null);
            $table->string('numero_interior', 5)->nullable()->default(null);
            $table->decimal('m2_terreno', 10, 2)->nullable()->default(null);
            $table->string('status')->nullable()->default(null);
            $table->decimal('medidas_1', 10, 3)->nullable()->default(null);
            $table->decimal('medidas_2', 10, 3)->nullable()->default(null);
            $table->decimal('medidas_3', 10, 3)->nullable()->default(null);
            $table->decimal('medidas_4', 10, 3)->nullable()->default(null);
            $table->string('colindancia_1', 120)->nullable()->default(null);
            $table->string('colindancia_2', 120)->nullable()->default(null);
            $table->string('colindancia_3', 120)->nullable()->default(null);
            $table->string('colindancia_4', 120)->nullable()->default(null);
            $table->string('orientacion_1', 20)->nullable()->default(null);
            $table->string('orientacion_2', 20)->nullable()->default(null);
            $table->string('orientacion_3', 20)->nullable()->default(null);
            $table->string('orientacion_4', 20)->nullable()->default(null);
            $table->decimal('_map_x', 10, 3)->nullable()->default(null);
            $table->decimal('_map_y', 10, 3)->nullable()->default(null);
            $table->unsignedInteger('vendedor_id')->nullable()->default(null);
            $table->unsignedInteger('prospecto_id')->nullable()->default(null);
            $table->dateTime('fecha_apartado')->nullable()->default(null);
            $table->unsignedTinyInteger('apartado_pagado')->default('0');
            $table->decimal('precio', 10, 2)->nullable()->default(null);
            $table->tinyInteger('apartado_enganche')->nullable()->default(null);
            $table->tinyInteger('apartado_mensualidades')->nullable()->default(null);
            $table->string('modo_apartado', 20)->nullable()->default(null);
            $table->decimal('precio_venta', 10, 2)->nullable()->default(null);
            $table->decimal('monto_mensualidad', 10, 2)->nullable()->default(null);
            $table->decimal('monto_pagado', 10, 2)->nullable()->default(null);
            $table->smallInteger('pagos_pagados')->nullable()->default(null);
            $table->timestamp('ultimo_pago')->nullable()->default(null);
            $table->dateTime('fecha_titulado')->nullable()->default(null);
            $table->json('citas_entrega')->nullable();
            $table->boolean('entrega')->default(0);
            $table->date('fecha_entrega')->nullable()->default(null);
            $table->json('documentos')->nullable();
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
        Schema::dropIfExists('inmuebles');
    }
}
