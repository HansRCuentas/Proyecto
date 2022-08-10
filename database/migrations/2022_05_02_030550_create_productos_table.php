<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('name',75);
            $table->text('descripcion')->nullable();
            $table->integer('nro_codigo')->unsigned()->nullable();
            $table->string('disponibilidad',15)->nullable()->default('stock');
            $table->integer('stock')->unsigned()->default(0);
            $table->integer('stock_minimo')->unsigned()->nullable()->default(0);
            $table->decimal('costo_producto',10,2);
            $table->decimal('precio_venta',10,2)->nullable();
            $table->string('imagen')->nullable();
            $table->integer('tipo')->unsigned();

            $table->bigInteger('categoria_id')->unsigned();
            $table->foreign('categoria_id')->references('id')->on('categorias');

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
        Schema::dropIfExists('productos');
    }
}
