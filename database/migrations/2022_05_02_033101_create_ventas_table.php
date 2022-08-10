<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha');
            $table->integer('nro_venta')->unsigned()->nullable();
            $table->string('region',75)->nullable();
            $table->decimal('total',10,2);
            $table->decimal('descuento',10,2)->nullable()->default(0);
            $table->string('tipo_pago',25)->nullable();
          

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('cliente_id')->references('id')->on('clientes');
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
        Schema::dropIfExists('ventas');
    }
}
