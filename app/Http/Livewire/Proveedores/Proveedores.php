<?php

namespace App\Http\Livewire\Proveedores;

use App\Models\Categoria;
use App\Models\Proveedor;
use Livewire\Component;
use Livewire\WithPagination;

class Proveedores extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Para validacion
    public $texto = "Hola";
    //Para editar un proveedor
    public $idp, $empresap, $celularp, $direccionp, $nitp, $categoriap, $categoriaidp;
    //Para buscar el proveedor
    public $buscar;
    //Para ordenar los proveedores
    public $direccion = 'asc';
    public $ordenar_por = 'empresa';
    protected $listeners = [
        'render' => 'render',
        'eliminar' => 'eliminar'
    ];


    public function updatingBuscar()
    {
        $this->resetPage();
    }
    public function render()
    {
        $categorias = Categoria::where('tipo', 3)->get();
        $proveedores = Proveedor::Where('empresa', 'like', '%' . $this->buscar . '%')
            ->orderBy($this->ordenar_por, $this->direccion)
            ->paginate(6);
                        
        //$proveedores = Proveedor::whereHas('categoria', function ($query) {
        //    $query->where('nombre', 'like', '%' . $this->buscar . '%');
        //})->get();

        return view('livewire.proveedores.proveedores', compact('proveedores', 'categorias'))
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
        } else {
            $this->ordenar_por = $campo;
            $this->direccion = 'asc';
        }
    }
    public function mostrar(Proveedor $prov)
    {
        $this->idp = $prov->id;
        $this->empresap = $prov->empresa;
        $this->celularp = $prov->celular;
        $this->direccionp = $prov->direccion;
        $this->nitp = $prov->nit;
        $this->categoriap = $prov->categoria->nombre;
        $this->categoriaidp = $prov->categoria_id;
    }

    public function modificar()
    {
        $rules2 = [
            'empresap' => "required|min:2|unique:proveedores,empresa,$this->idp",
            'celularp' => 'required',
            'direccionp' => 'required'
        ];
        $messages = [
            'empresap.required' => 'El nombre de empresa es requerido',
            'empresap.min' => 'El nombre de empresa debe tener al menos 2 caracteres',
            'empresap.unique' => 'El nombre de empresa ya existe',
            'celularp.required' => 'El celular es requerido',
            'direccionp.required' => 'La direccion es requerida'
        ];
        $this->validate($rules2, $messages);

        $prove = Proveedor::find($this->idp);
        $prove->empresa = $this->empresap;
        $prove->celular = $this->celularp;
        $prove->direccion = $this->direccionp;
        $prove->nit = $this->nitp;
        $prove->categoria_id = $this->categoriaidp;

        $prove->save();
        $this->emit('ocultarEdit', 'El proveedor se modifico satisfactoriamente');
        $this->resetear();
    }
    public function eliminar(Proveedor $prov)
    {
        $prov->delete();
    }
    public function resetear()
    {
        $this->resetErrorBag(['empresap', 'celularp', 'direccionp']);
    }
}
