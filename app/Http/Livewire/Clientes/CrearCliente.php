<?php

namespace App\Http\Livewire\Clientes;

use Livewire\Component;
use App\Models\Cliente;
class CrearCliente extends Component
{
    //Datos para crear cliente
    public $nombre,$celular,$direccion,$region,$cedula;
    public function render()
    {
        return view('livewire.clientes.crear-cliente');
    }
    public function guardarCliente()
    {
        //Registra a un cliente pero primero se valida los datos
        $rules2=[
            'nombre'=>"required",
            'direccion'=>'required',
            'region'=>'required',
        ];
        $messages=[
            'nombre.required'=>'El nombre de cliente es requerido',
            'direccion.required'=>'La direccion es requerida',
            'region.required'=>'La region es requerida'
        ];
        $this->validate($rules2,$messages);
        $clie=new Cliente;
        $clie->nombre=$this->nombre;
        $clie->celular=$this->celular;
        $clie->direccion=$this->direccion;
        $clie->region=$this->region;
        $clie->cedula=$this->cedula;
        $clie->save();
        $this->emitTo('clientes.clientes','render');
        $this->emit('ocultarCrear', 'El Cliente se creo satisfactoriamente');
        $this->resetear();
    }
    public function resetear()
    {
        $this->reset(['nombre','celular','direccion','region','cedula']);
        $this->resetErrorBag(['nombre','direccion','region']);
    }
}
