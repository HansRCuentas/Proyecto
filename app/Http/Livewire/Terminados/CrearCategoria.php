<?php

namespace App\Http\Livewire\Terminados;

use App\Models\Categoria;
use Livewire\Component;

class CrearCategoria extends Component
{
    public $nombre,$descripcion;
    
    public function render()
    {
        return view('livewire.terminados.crear-categoria');
    }
    public function guardarCategoria()
    {
        $rules2=[
            'nombre'=>'required|min:2|unique:categorias,nombre',
        ];
        $messages=[
            'nombre.required'=>'El nombre de categoria es requerido',
            'nombre.min'=>'El nombre de categoria debe tener al menos dos caracteres',
            'nombre.unique'=>'El nombre de categoria ya existe'
        ];
        $this->validate($rules2,$messages);
        $cat=new Categoria;
        $cat->nombre=$this->nombre;
        $cat->descripcion=$this->descripcion;
        $cat->tipo=2;
        $cat->save();
        //Se resetea los campos
        $this->resetear();
        //Se realiza un emit para avisar que se renderize
        $this->emitTo('terminados.categoria-terminados','render');
        $this->emit('ocultarCrear','La categoria se creo satisfactoriamente');

    }
    public function resetear()
    {
        $this->reset(['nombre','descripcion']);
        $this->resetErrorBag('nombre','descripcion');
    }
}
