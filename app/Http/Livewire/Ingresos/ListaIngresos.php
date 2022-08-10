<?php

namespace App\Http\Livewire\Ingresos;

use Livewire\Component;
use App\Models\Ingreso;
use App\Models\Producto;
use Livewire\WithPagination;

class ListaIngresos extends Component
{
    //Para la paginacion
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Para la busqueda
    public $buscar;
    public $direccion = "desc";
    public $ordenar_por = "fecha";
    //Para vaciar los productos
    public $productos = [];
    protected $listeners = ['eliminar'];
    public function updatingBuscar()
    {
        $this->resetPage();
    }
    public function render()
    {
        $ingresos = Ingreso::where('fecha', 'like', '%' . $this->buscar . '%')
            ->orderBy($this->ordenar_por, $this->direccion)
            ->paginate(6);
        return view('livewire.ingresos.lista-ingresos', compact('ingresos'))
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
            $this->direccion = 'desc';
        }
    }
    public function eliminar(Ingreso $ingreso)
    {
        $this->productos = $ingreso->productos;
        foreach ($this->productos as $prod) {
            $producto = Producto::find($prod->id);
            $producto->stock = $producto->stock - $prod->pivot->cantidad;
            $ingreso->productos()->detach($prod->id);
            $producto->save();
        }

        $ingreso->delete();
    }
}
