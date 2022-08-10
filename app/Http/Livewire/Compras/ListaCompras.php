<?php

namespace App\Http\Livewire\Compras;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Compra;
use App\Models\Proveedor;
use App\Models\User;
use Livewire\WithPagination;

class ListaCompras extends Component
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
    //Variables para mostrar el detalle de la compra
    public $comprap,$proveedor,$usuario;

    public function updatingBuscar()
    {
        $this->resetPage();
    }
    public function mount()
    {
        $this->comprap=new Compra;
        $this->usuario=new User;
        $this->proveedor=new Proveedor;
    }
    public function render()
    {
        $compras=Compra::whereHas('proveedor', function ($query) {
            $query->where('empresa', 'like', '%' . $this->buscar . '%');
        })
            ->whereIn('tipo_pago',[1,4])

         
            ->orWhere('fecha','like','%'. $this->buscar .'%')
            ->whereIn('tipo_pago',[1,4])
           
            ->orderBy($this->ordenar_por, $this->direccion)
            ->paginate(6);
        return view('livewire.compras.lista-compras', compact('compras'))
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
    public function eliminar(Compra $compra)
    {
        //Elimina una compra y devuelve el stock
        $this->productos=$compra->productos;
        foreach($this->productos as $prod){
            $producto=Producto::find($prod->id);
            $producto->stock = $producto->stock - $prod->pivot->cantidad;
            $compra->productos()->detach($prod->id);
            $producto->save();
        }
        $compra->delete();
    }
    public function mostrar(Compra $compra)
    {
        $this->comprap= $compra;
        $this->usuario= $compra->user;
        $this->proveedor= $compra->proveedor;
    }
}
