<?php

namespace App\Http\Livewire\Proveedores;

use Livewire\Component;
use App\Models\Proveedor;
use App\Models\Categoria;
class CrearProveedor extends Component
{
    //Valores para la creacion
    public $empresa,$celular,$direccion,$nit,$categoria_id;

    public function render()
    {
        $categorias=Categoria::where('tipo',3)->get();
        return view('livewire.proveedores.crear-proveedor',compact('categorias'));
    }
    public function guardarProveedor()
    {
        $this->validate([
            'empresa'=>'required|min:2|unique:proveedores,empresa',
            'celular'=>'required',
            'direccion'=>'required',
            'categoria_id'=>'required'
        ]);
        $prov=new Proveedor;
        $prov->empresa=$this->empresa;
        $prov->celular=$this->celular;
        $prov->direccion=$this->direccion;
        $prov->nit=$this->nit;
        $prov->categoria_id=$this->categoria_id;
        $prov->save();
        //Se resetea los campos
        $this->resetear();
        //Se realiza un emit para avisar que se renderize
        $this->emitTo('proveedores.proveedores','render');
      
        $this->emit('ocultarGuardar','El proveedor se creo satisfactoriamente');

    }
    public function resetear()
    {
        $this->reset(['empresa','celular','direccion','nit','categoria_id']);
        $this->resetErrorBag(['empresa','celular','direccion','categoria_id']);
    }
}
