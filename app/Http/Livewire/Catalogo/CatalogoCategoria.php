<?php

namespace App\Http\Livewire\Catalogo;

use Livewire\Component;
use App\Models\Categoria;
use App\Models\Producto;
class CatalogoCategoria extends Component
{
    public $catId;
    public $buscar;
    public function mount($id)
    {
        $this->catId=$id;
    }
    public function render()
    {
        $categoria=Categoria::find($this->catId);
        $productos=Producto::where('categoria_id',$this->catId)
        ->where('nombre' ,'like', '%'. $this->buscar . '%') 
        ->where('indicador',1)
        ->get();
        $categorias=Categoria::where('tipo',2)->get();
        return view('livewire.catalogo.catalogo-categoria', compact('categoria','productos','categorias'))
        ->extends('admin.inicio')
        ->section('contenido');
    
    }
}
