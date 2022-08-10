<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Venta;
use App\Models\Ingreso;
class Cliente extends Model
{
    use HasFactory;
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
    public function ingresos()
    {
        return $this->hasMany(Ingreso::class);
    }
}
