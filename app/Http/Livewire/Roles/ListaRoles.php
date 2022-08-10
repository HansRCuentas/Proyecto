<?php

namespace App\Http\Livewire\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
class ListaRoles extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Para editar el rol;
    public $idr,$nombrer;
    //Para crear el rol
    public $nombre;
    //Escuchadores
    public $listeners=[
        'render'=>'render',
        'eliminar'=>'eliminar'
    ];
    public function updatingBuscar()
    {
        $this->resetPage();
    }
    public function render()
    {
        $roles=Role::paginate(6);
        return view('livewire.roles.lista-roles',compact('roles'))
        ->extends('adminlte::page')
        ->section('content');
    }
    public function editar(Role $role)
    {
        //Para mostrar los datos en el modal
        $this->idr=$role->id;
        $this->nombrer=$role->name;
    }
    public function modificar()
    {
        //Para modificar mediante el boton de guardar rol
        $rules2=[
            'nombrer'=>"required|unique:roles,name,$this->idr"
        ];
        $messages=[
            'nombrer.required'=>'El nombre de rol es requerido',
            'nombrer.unique'=>'Ya existe el nombre de rol'
        ];
        $this->validate($rules2,$messages);
        $role=Role::find($this->idr);
        $role->name=$this->nombrer;
        $this->emit('alertaRapida-success-editar','Rol modificado satisfactoriamente');
       
        $role->save();

    }
    public function guardar()
    {
        $rules2=[
            'nombre'=>"required|unique:roles,name"
        ];
        $messages=[
            'nombre.required'=>'El nombre de rol es requerido',
            'nombre.unique'=>'Ya existe el nombre de rol'
        ];
        $role = Role::create(['name' => $this->nombre]);
        $this->emit('alertaRapida-success-crear','Rol creado satisfactoriamente');
        $this->resetear();
    }
    public function resetear()
    {
        $this->reset(['nombre']);
        $this->resetErrorBag(['nombre']);
    }
    public function eliminar(Role $role)
    {
        $role->delete();
    }

}
