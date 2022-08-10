<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductoSalidaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto_salida', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad')->unsigned();

            $table->unsignedBigInteger('salida_id');
            $table->unsignedBigInteger('producto_id');
            
            $table->foreign('salida_id')->references('id')->on('salidas');
            $table->foreign('producto_id')->references('id')->on('productos');
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
        Schema::dropIfExists('producto_salida');
    }
}
