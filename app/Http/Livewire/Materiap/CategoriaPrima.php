<?php

namespace App\Http\Livewire\Materiap;

use Livewire\Component;
use App\Models\Categoria;
use Livewire\WithPagination;

class CategoriaPrima extends Component
{
    //CATEGORIAS MATERIA PRIMA
    //Para la paginacion
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Variables para editar Ca  tegoria
    public $idp,$nombre,$descripcion;
    //Variables para buscar
    public $buscar;
    public $direccion='asc';
    public $ordenar_por='nombre';
    protected $listeners=['render','eliminar'];

    public function render()
    {
        $categorias= Categoria::where('tipo','like',1)
                    ->Where('nombre','like', '%' . $this->buscar . '%')
                    ->orwhere('descripcion','like','%' . $this->buscar . '%')
                    ->where('tipo','like',1)
                    ->orderBy($this->ordenar_por, $this->direccion)
                    ->paginate(6);

        return view('livewire.materiap.categoria-prima',compact('categorias'))
        ->extends('adminlte::page')
        ->section('content');
    }
    public function updatingBuscar()
    {
        $this->resetPage();
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
        $this->idp=$cat->id;
        $this->nombre=$cat->nombre;
        $this->descripcion=$cat->descripcion;
    }
    public function modificar()
    {
        $rules2=[
            'nombre'=>"required|min:2|unique:categorias,nombre,$this->idp"
        ];
        $messages=[
            'nombre.required'=>'El nombre de categoria es requerido',
            'nombre.min'=>'El nombre de categoria debe tener al menos 2 caracteres',
            'nombre.unique'=>'El nombre de categoria ya existe'
        ];
        $this->validate($rules2,$messages);
        //Se llenana los campos y se guarda el registro
        $cat=Categoria::find($this->idp);
        $cat->nombre=$this->nombre;
        $cat->descripcion=$this->descripcion;
        $cat->tipo=1;
        $cat->save();
        $this->resetear();
        $this->emit('ocultarEditar', 'La Categoria se Modifico satisfactoriamente');
    }
    public function eliminar(Categoria $cat)
    {
        $cat->delete();
    }
    public function resetear()
    {
        $this->resetErrorBag('nombre');
    }


}
