<?php

namespace App\Http\Livewire\Proveedores;

use Livewire\Component;
use App\Models\Categoria;
use Livewire\WithPagination;

class CategoriasProveedores extends Component
{
    use WithPagination;
    //Objeto de tipo categoria para Editar
  
    //Variables para editar categoria;
    public $catNom,$catDesc,$catId;
    //
    protected $paginationTheme = 'bootstrap';
    public $buscar;
    public $direccion='asc';
    public $ordenar_por='nombre';
    protected $listeners=['render'=>'render', 
    'eliminar'=>'eliminar'];

    public function updatingBuscar()
    {
        $this->resetPage();
    }
    public function render()
    {
        $categorias= Categoria::where('tipo','like',3)
                    ->Where('nombre','like', '%' . $this->buscar . '%')
                    ->orwhere('descripcion','like','%' . $this->buscar . '%')
                    ->where('tipo','like',3)
                    ->orderBy($this->ordenar_por, $this->direccion)
                    ->paginate(6);
        return view('livewire.proveedores.categorias-proveedores',compact('categorias'))
        ->extends('adminlte::page')
        ->section('content');
    }
    public function ordenar($campo)
    {

        if ($this->ordenar_por == $campo) {

            if ($this->direccion == 'desc') {
                $this->direccion = 'asc';
            } else {
                $this->direccion = 'desc';
            }
        } 
        else {
            $this->ordenar_por = $campo;
            $this->direccion = 'asc';
         }
    }
    public function editar(Categoria $cat)
    {
        $this->catId=$cat->id;
        $this->catNom=$cat->nombre;
        $this->catDesc=$cat->descripcion;
    }
    public function mostrar(Categoria $cat){
        $this->catId=$cat->id;
        $this->catNom=$cat->nombre;
        $this->catDesc=$cat->descripcion;
    }
    public function modificar()
    {
        $rules2=[
            'catNom'=>"required|min:2|unique:categorias,nombre,$this->catId"
        ];
        $messages=[
            'catNom.required'=>'El nombre de categoria es requerido',
            'catNom.min'=>'El nombre de categoria debe tener al menos 2 caracteres',
            'catNom.unique'=>'El nombre de categoria ya existe'
        ];
        $this->validate($rules2,$messages);
     
        $cat=Categoria::find($this->catId);
        $cat->nombre=$this->catNom;
        $cat->descripcion=$this->catDesc;
        $cat->tipo=3;

        $cat->save();

        $this->resetear();
        //$this->emitTo('proveedores.categorias-proveedores','render');
        //$this->generarId();
        $this->emit('ocultar2', 'La Categoria se Modifico satisfactoriamente');
    }

    public function resetear(){
        $this->resetErrorBag('nombre');
    }
    public function eliminar(Categoria $cat)
    {
        $cat->delete();
    }
}
