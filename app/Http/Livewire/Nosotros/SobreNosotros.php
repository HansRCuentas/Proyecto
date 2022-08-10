<?php

namespace App\Http\Livewire\Nosotros;

use Livewire\Component;
use App\Models\Categoria;
class SobreNosotros extends Component
{
    public $catId;
    public function render()
    {
        $categorias=Categoria::get();
        return view('livewire.nosotros.sobre-nosotros',compact('categorias'))
        ->extends('admin.inicio')
        ->section('contenido');
    }
}
