<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrototiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prototipos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('desarrollo_id');
            $table->unsignedInteger('empresa_id');
            $table->string('nombre');
            $table->decimal('m2_construccion', 10, 2);
            $table->decimal('niveles', 10, 2);
            $table->decimal('recamaras', 10, 2);
            $table->decimal('baños', 10, 2);
            $table->text('observaciones');
            $table->decimal('precio', 10, 2);
            $table->json('fotos')->nullable()->default(null);
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
        Schema::dropIfExists('prototipos');
    }
}
