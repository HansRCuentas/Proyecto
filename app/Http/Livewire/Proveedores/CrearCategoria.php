<?php

namespace App\Http\Livewire\Proveedores;

use App\Models\Categoria;
use Livewire\Component;

class CrearCategoria extends Component
{
    public $open=true;
    //Para resetear la validacion
    public $texto="Hola";
    //Valores para la creacion
    public $nombre;
    public $descripcion;
    //reglas de validacion
    protected $rules=[
        'nombre'=>'required|min:2|unique:categorias,nombre'
    ];
   /*  public function updatingOpen()
    {
        $this->resetear();
    }
 */
    public function render()
    {
        return view('livewire.proveedores.crear-categoria');
    }
    public function guardarCategoria()
    {
        $messages=[
            'nombre.required'=>'El nombre de categoria es requerido',
            'nombre.min'=>'El nombre de categoria debe tener al menos 2 caracteres',
            'nombre.unique'=>'El nombre de categoria ya existe'
        ];
        $this->validate($this->rules,$messages);
     
        $cat=new Categoria;
        $cat->nombre=$this->nombre;
        $cat->descripcion=$this->descripcion;
        $cat->tipo=3;
        $cat->save();

        $this->resetear();
        $this->emitTo('proveedores.categorias-proveedores','render');
        //$this->generarId();
        $this->emit('ocultar', 'La Categoria se creó satisfactoriamente');
        

    }
    public function resetear(){
        $this->reset(['nombre','descripcion']);
        $rules2=['texto'=>'required'];
        $this->validate($rules2);
    }
}
