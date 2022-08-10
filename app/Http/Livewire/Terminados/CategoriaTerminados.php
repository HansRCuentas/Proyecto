<?php

namespace App\Http\Livewire\Terminados;

use Livewire\Component;
use App\Models\Categoria;
use Livewire\WithPagination;

class CategoriaTerminados extends Component
{
    //Para la paginacion
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Variables para editar categoria
    public $nombret,$descripciont,$idt;
    //Variables para buscar
    public $buscar;
    public $direccion='asc';
    public $ordenar_por='nombre';
    protected $listeners=['render','eliminar'];
    public function render()
    {
        $categorias= Categoria::where('tipo','like',2)
                    ->Where('nombre','like', '%' . $this->buscar . '%')
                    ->orwhere('descripcion','like','%' . $this->buscar . '%')
                    ->where('tipo','like',2)
                    ->orderBy($this->ordenar_por, $this->direccion)
                    ->paginate(6);

        //$categorias=DB::select("select * from categorias where tipo=3 and (nombre like '%$this->buscar%' or descripcion like '%$this->buscar%')")->paginate(6);
        return view('livewire.terminados.categoria-terminados',compact('categorias'))
        ->extends('adminlte::page')
        ->section('content');
    }
    public function updatingBuscar()
    {
        $this->resetPage();
    }
    public function ordenar($campo)
    {
        //Para ordenar segun el campo de la tabla
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
        $this->idt=$cat->id;
        $this->nombret=$cat->nombre;
        $this->descripciont=$cat->descripcion;
    }
    public function modificar()
    {
        $rules2=[
            'nombret'=>"required|min:2|unique:categorias,nombre,$this->idt"
        ];
        $messages=[
            'nombret.required'=>'El nombre de categoria es requerido',
            'nombret.min'=>'El nombre de categoria debe tener al menos 2 caracteres',
            'nombret.unique'=>'El nombre de categoria ya existe'
        ];
        $this->validate($rules2,$messages);
        //Se llenana los campos y se guarda el registro
        $cat=Categoria::find($this->idt);
        $cat->nombre=$this->nombret;
        $cat->descripcion=$this->descripciont;
        $cat->tipo=2;
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
        $this->resetErrorBag('nombret');
    }
}
