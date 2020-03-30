<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('inmuebles', function (Blueprint $table) {
            $table->json('citas_entrega')->nullable()->after('fecha_titulado');
            $table->json('documentos')->nullable()->after('fecha_titulado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inmuebles', function (Blueprint $table) {
            $table->dropColumn('citas_entrega');
            $table->dropColumn('documentos');
        });
    }
}
