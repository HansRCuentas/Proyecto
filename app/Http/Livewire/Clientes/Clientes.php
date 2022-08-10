<?php

namespace App\Http\Livewire\Clientes;

use App\Models\Cliente;
use Livewire\Component;
use Livewire\WithPagination;

class Clientes extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Para editar cliente
    public $idc,$nombre,$celular,$direccionc,$region,$cedula;
    //Para buscar cliente
    public $buscar;
    //Para ordenar segun el campo
    public $direccion = 'asc';
    public $ordenar_por = 'nombre';
    //Escuchadores
    protected $listeners=[
        'render'=>'render',
        'eliminar'=>'eliminar'
    ];
    public function updatingBuscar()
    {
        $this->resetPage();
    }

    public function render()
    {
        $clientes=Cliente::where('nombre', 'like', '%' . $this->buscar . '%')
        ->orWhere('region','like', '%' . $this->buscar . '%')
        ->orWhere('cedula','like', '%' . $this->buscar . '%')
        ->orderBy($this->ordenar_por, $this->direccion)
        ->paginate(6);
        return view('livewire.clientes.clientes',compact('clientes'))
        ->extends('adminlte::page')->section('content');
    }
    public function ordenar($campo)
    {

        if ($this->ordenar_por == $campo) {

            if ($this->direccion == 'desc') {
                $this->direccion = 'asc';
            } else {
                $this->direccion = 'desc';
            }
        } else {
            $this->ordenar_por = $campo;
            $this->direccion = 'asc';
        }
    }
    public function editar(Cliente $clie)
    {
        //Muestra los datos del cliente en el modal de editar
        $this->idc=$clie->id;
        $this->nombre=$clie->nombre;
        $this->celular=$clie->celular;
        $this->direccionc=$clie->direccion;
        $this->region=$clie->region;
        $this->cedula=$clie->cedula;
    }
    public function modificar()
    {
        $rules2=[
            'nombre'=>"required",
            'direccionc'=>'required',
            'region'=>'required',
        ];
        $messages=[
            'nombre.required'=>'El nombre de cliente es requerido',
            'direccionc.required'=>'La direccion es requerida',
            'region.required'=>'La region es requerida'
        ];
        $this->validate($rules2,$messages);

        $clie=Cliente::find($this->idc);
        $clie->nombre=$this->nombre;
        $clie->celular=$this->celular;
        $clie->direccion=$this->direccionc;
        $clie->region=$this->region;
        $clie->cedula=$this->cedula;
        $clie->save();
        $this->emit('ocultarEditar', 'El Cliente se modifico satisfactoriamente');
        $this->resetear();
    }
    public function eliminar(Cliente $clie)
    {
        $clie->delete();
    }
    public function resetear()
    {
        $this->resetErrorBag(['nombre','direccionc','region']);
    }
}
