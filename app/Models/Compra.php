<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\User;

class Compra extends Model
{
    use HasFactory;
    public function productos()
    {
        return $this->belongsToMany(Producto::class)->withPivot(["cantidad"]);
    }
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
