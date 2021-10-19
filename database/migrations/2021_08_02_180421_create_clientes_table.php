<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('desarrollo_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('empresa_id');
            $table->string('hash', 10);
            $table->string('nombre');
            $table->json('anexo_detalles')->nullable()->default(null);
            $table->string('status')->nullable()->default(null);
            $table->tinyInteger('validado')->nullable()->default('0');
            $table->smallInteger('completado');
            $table->string('referencia_bancaria', 15);
            $table->string('condicion')->default('prospecto')->nullable();
            $table->timestamp('condicion_changed')->nullable()->default(null);
            $table->boolean('telemarketing')->nullable()->default(null);
            $table->string('condicion_telemarketing')->nullable();
            $table->json('anexo_referencias')->nullable()->default(null);
            $table->json('anexo_credito')->nullable()->default(null);
            $table->json('anexo_conyuge')->nullable()->default(null);
            $table->json('anexo_trabajo')->nullable()->default(null);
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
        Schema::dropIfExists('clientes');
    }
}
