<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Init extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Desarrollos
        if (!Schema::hasTable('desarrollos')) {
            Schema::create('desarrollos', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('nombre');
                $table->string('mapa_file');
                $table->nullableTimestamps();
            });
        }

        // Prototipos
        if (!Schema::hasTable('prototipos')) {
            Schema::create('prototipos', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedInteger('id_desarrollo');
                $table->string('nombre');
                $table->decimal('m2_construccion', 10, 2);
                $table->decimal('niveles', 10, 2);
                $table->decimal('recamaras', 10, 2);
                $table->decimal('baños', 10, 2);
                $table->text('observaciones');
                $table->decimal('precio', 10, 2);
                $table->json('fotos')->nullable()->default(null);
                $table->nullableTimestamps();
            });
        }

        // Inmuebles
        if (!Schema::hasTable('inmuebles')) {
            Schema::create('inmuebles', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedInteger('id_prototipo');
                $table->string('manzana', 4)->nullable()->default(null);
                $table->string('lote')->nullable()->default(null);
                $table->string('calle', 150)->nullable()->default(null);
                $table->smallInteger('numero')->nullable()->default(null);
                $table->string('numero_interior', 5)->nullable()->default(null);
                $table->decimal('m2_terreno', 10, 2)->nullable()->default(null);
                $table->enum('status', ['libre','apartado','vencido','titulado'])->nullable()->default(null);
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
                $table->string('extras', 1)->nullable()->default(null);
                $table->decimal('_map_x', 10, 3)->nullable()->default(null);
                $table->decimal('_map_y', 10, 3)->nullable()->default(null);
                $table->unsignedInteger('id_vendedor')->nullable()->default(null);
                $table->unsignedInteger('id_prospecto')->nullable()->default(null);
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
                $table->nullableTimestamps();
                $table->softDeletes();
            });
        }

        // Clientes
        if (!Schema::hasTable('clientes')) {
            Schema::create('clientes', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedInteger('id_desarrollo');
                $table->unsignedInteger('id_usuario');
                $table->string('hash', 10);
                $table->string('nombre');
                $table->json('anexo_detalles')->nullable()->default(null);
                $table->enum('status', ['integracion','oficina','notaria','ar_ci','titulado'])->nullable()->default(null);
                $table->tinyInteger('validado')->nullable()->default('0');
                $table->smallInteger('completado');
                $table->string('referencia_bancaria', 15);
                $table->json('anexo_referencias')->nullable()->default(null);
                $table->json('anexo_credito')->nullable()->default(null);
                $table->json('anexo_conyuge')->nullable()->default(null);
                $table->json('anexo_trabajo')->nullable()->default(null);
                $table->nullableTimestamps();
                $table->softDeletes();
            });
        }

        // Clientes Inmuebles
        if (!Schema::hasTable('cliente_inmueble')) {
            Schema::create('cliente_inmueble', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedInteger('id_cliente');
                $table->unsignedInteger('id_inmueble');
                $table->enum('tipo', ['deposito','efectivo']);
                $table->string('numero_recibo', 15);
                $table->string('referencia', 15);
                $table->dateTime('fecha');
                $table->decimal('monto', 10, 2);
                $table->decimal('monto_total', 10, 2);
                $table->string('comentario');
                $table->nullableTimestamps();
                $table->softDeletes();
            });
        }

        // Seguimientos
        if (!Schema::hasTable('seguimientos')) {
            Schema::create('seguimientos', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedInteger('id_cliente');
                $table->unsignedInteger('id_usuario');
                $table->enum('tipo', ['cita','llamada','visita_fraccionamiento','comentario','nuevo','tarea','messenger_fb', 'correo_electronico', 'otro'])->nullable()->default(null);
                $table->timestamp('fecha')->nullable()->default(null);
                $table->text('mensaje')->nullable()->default(null);
                $table->tinyInteger('completado')->nullable()->default(null);                
                $table->nullableTimestamps();
                $table->softDeletes();
            });
        }

        // Documentos
        if (!Schema::hasTable('documentos')) {
            Schema::create('documentos', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedInteger('id_usuario');
                $table->unsignedInteger('id_cliente')->nullable()->default(null);
                $table->unsignedInteger('id_desarrollo')->nullable()->default(null);
                $table->string('nombre');
                $table->string('path')->nullable();
                $table->tinyInteger('validado')->nullable()->default(null);
                $table->nullableTimestamps();
            });
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documentos');

        Schema::dropIfExists('seguimientos');

        Schema::dropIfExists('cliente_inmueble');

        Schema::dropIfExists('clientes');

        Schema::dropIfExists('inmuebles');

        Schema::dropIfExists('prototipos');

        Schema::dropIfExists('desarrollos');
    }
}
