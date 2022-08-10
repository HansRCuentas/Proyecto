<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Producto;
use App\Models\User;
class Ingreso extends Model
{
    use HasFactory;
    public function productos()
    {
        return $this->belongsToMany(Producto::class)->withPivot(["cantidad"]);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}