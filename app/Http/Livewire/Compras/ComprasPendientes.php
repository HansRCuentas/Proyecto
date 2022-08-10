<?php

namespace App\Http\Livewire\Compras;

use Livewire\Component;
use App\Models\Compra;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\User;
use Livewire\WithPagination;

class ComprasPendientes extends Component
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
    //Varibles para mostrar en el modal de pago
    public $idc, $nro_comprap, $fechap, $efectivop, $totalp,
        $saldop, $montop, $cambiop;

    //Variables para mostrar una venta
    public $comprap, $proveedor, $usuario;
    protected $listeners = ['eliminar'];
    public function updatedMontop()
    {
        if ($this->montop >= $this->saldop) {
            $this->cambiop = $this->montop - $this->saldop;
        } else {
            $this->cambiop = 0;
        }
    }
    public function mount()
    {
        $this->comprap = new Compra;
        $this->usuario = new User;
        $this->proveedor = new Proveedor;
    }
    public function updatingBuscar()
    {
        $this->resetPage();
    }
    public function render()
    {
        $compras = Compra::whereHas('proveedor', function ($query) {
            $query->where('empresa', 'like', '%' . $this->buscar . '%');
        })
            ->whereIn('tipo_pago', [2, 3])
            ->orWhere('fecha', 'like', '%' . $this->buscar . '%')
            ->whereIn('tipo_pago', [2, 3])
            ->orderBy($this->ordenar_por, $this->direccion)
            ->paginate(6);
        return view('livewire.compras.compras-pendientes', compact('compras'))
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
        $this->productos = $compra->productos;
        foreach ($this->productos as $prod) {
            $producto = Producto::find($prod->id);
            $producto->stock = $producto->stock - $prod->pivot->cantidad;
            $compra->productos()->detach($prod->id);
            $producto->save();
        }
        $compra->delete();
    }
    public function pagar(Compra $compra)
    {
        $this->idc = $compra->id;
        $this->nro_comprap = $compra->nro_compra;
        $this->fechap = $compra->fecha;
        $this->efectivop = $compra->efectivo;
        $this->totalp = $compra->total;
        $this->saldop = $compra->total - $compra->efectivo;
        $this->reset(['montop', 'cambiop']);
    }
    public function ajustar()
    {
        $this->montop = $this->saldop;
    }
    public function registrarPago()
    {
        if ((float) $this->montop <= 0) {
            $this->emit('alertaRapida-error', 'Debe ingresar el monto a cancelar');
            return;
        }
        $compra = Compra::find($this->idc);
        if ((float) $this->montop >= (float) $this->saldop) {
            $compra->tipo_pago = 4;
            $compra->efectivo = $compra->efectivo + (float) $this->montop;
        } else {
            $compra->efectivo = $compra->efectivo + (float)$this->montop;
        }
        $compra->save();
        $this->emit('alertaRapida', 'Pago registrado correctamente');
    }
    public function mostrar(Compra $compra)
    {
        $this->comprap = $compra;
        $this->usuario = $compra->user;
        $this->proveedor = $compra->proveedor;
    }
}
