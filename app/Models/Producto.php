<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categoria;
use App\Models\Venta;
use App\Models\Ingreso;
use App\Models\Compra;
use App\Models\Salida;

class Producto extends Model
{
    use HasFactory;
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
    public function ventas()
    {
        return $this->belongsToMany(Venta::class)->withPivot(["cantidad"]);
    }
    public function compras()
    {
        return $this->belongsToMany(Compra::class)->withPivot(["cantidad"]);
    }
    public function salidas()
    {
        return $this->belongsToMany(Salida::class)->withPivot(["cantidad"]);
    }
    public function ingresos()
    {
        return $this->belongsToMany(Ingreso::class)->withPivot(["cantidad"]);
    }
    public function precios()
    {
        return $this->hasMany(Precio::class);
    }

}
