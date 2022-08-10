<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Proveedor;
use App\Models\Producto;

class Categoria extends Model
{
    use HasFactory;
    public function proveedores()
    {
        return $this->hasMany(Proveedor::class);
    }
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
