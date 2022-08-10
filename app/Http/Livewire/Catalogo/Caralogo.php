<?php

namespace App\Http\Livewire\Catalogo;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Categoria;

class Caralogo extends Component
{
    public $titulo;
    public $buscar='';
    public $estado=false;
    public function render()
    {
        //obtiene los productos y categorias de la base de datos y los manda a la pagina
        $productos2 = [];
        $categorias=[];
        if (strLen($this->buscar) > 0) {
            $productos2 = Producto::where('nombre', 'like', '%' . $this->buscar . '%')
                ->where('tipo',2)
                ->where('indicador',1)
                ->get();
            $categorias = [];
            $this->estado=true;
        } else {
            if($this->estado){

                $this->redireccionar();
                $this->estado=false;
            }
            $categorias = Categoria::where('tipo',2)->get();

        }

        return view('livewire.catalogo.caralogo',compact('categorias','productos2'))
        ->extends('admin.inicio')
        ->section('contenido');
    }
    public function redireccionar()
    {
        return redirect()->route('catalogo');
    }

    public function obtener()
    {
        return true;
    }
}
