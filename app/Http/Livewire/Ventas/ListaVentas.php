<?php

namespace App\Http\Livewire\Ventas;

use Livewire\Component;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\User;
use Livewire\WithPagination;

class ListaVentas extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Variables para buscar y ordenar
    public $buscar;
    public $direccion = "desc";
    public $ordenar_por = "fecha";
    //Variable para mostrar la venta
    public $venta2;
    //Variable para guardar los productos
    public $productos = [];
    public $user, $cliente;
    //Total Medio
    public $total_medio;
    //Reglas de validacion
    protected $rules = [
        'venta2.fecha' => 'required',
        'venta2.cliente_id' => 'required',
        'venta2.cliente.nombre' => 'required'
    ];
    public function updatingBuscar()
    {
        $this->resetPage();
    }
    protected $listeners = [
        'eliminar'
    ];

    public function mount()
    {
        $this->user = new User();
        $this->venta2 = new Venta();

        //Nuevo para mostrar cliente
        $this->cliente = new Cliente;
    }
    public function render()
    {

        $ventas = Venta::whereHas('cliente', function ($query) {
            $query->where('nombre', 'like', '%' . $this->buscar . '%');
        })
            ->where('estado', 1)
            ->orderBy($this->ordenar_por, $this->direccion)
            ->paginate(4);
        return view('livewire.ventas.lista-ventas', compact('ventas'))
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
    public function mostrar(Venta $venta)
    {
        $this->venta2 = $venta;
        $this->cliente = $venta->cliente;
        $this->user = $venta->user;
        $totalm = 0;
        foreach ($venta->productos as $prod) {
            $totalm = $totalm + ($prod->pivot->cantidad * $prod->precio_venta);
        }
        $this->total_medio = $totalm;
    }
    public function eliminar(Venta $venta)
    {
        $this->mount();
        $this->productos = $venta->productos;
        foreach ($this->productos as $prod) {
            $producto = Producto::find($prod->id);
            $producto->stock = $producto->stock + $prod->pivot->cantidad;
            $venta->productos()->detach($prod->id);
            $producto->save();
        }
        $venta->delete();
    }
    public function descargarPdf(Venta $venta)
    {

        return redirect()->route('pdf', $venta->documento_pdf);
    }
}
