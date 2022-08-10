<?php

namespace App\Http\Livewire\Ventas;

use Livewire\Component;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\User;
use Livewire\WithPagination;

class ListarVentasPendientes extends Component
{
    //Para la paginacion
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Variables para buscar y ordenar
    public $buscar;
    public $direccion = "desc";
    public $ordenar_por = "fecha";
    //Variables para la venta
    public $productos = [];
    public $ventap;
    //Variables para la edicion
    public $monto_cancelado,$total_venta,$saldo,$efectivo,$cambio;
    public $idv,$nro_venta,$fecha;
    //Total solo productos
    public $total_medio;

    //Cliente
    public $cliente;
    public $user;
    protected $listeners = [
        'eliminar'
    ];
    public function updatingBuscar()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->ventap = new Venta();
        $this->cliente=new Cliente();
      
        $this->user=new User();
    }
    public function updatedEfectivo()
    {
        //Actualiza el efectivo
        if($this->efectivo >= $this->saldo){
            $this->cambio= $this->efectivo - $this->saldo;
        }else{
            $this->cambio=0;
        }
    }
    public function render()
    {
        //Envia los datos principales a la vista
        $ventas=Venta::whereHas('cliente', function ($query) {
            $query->where('nombre', 'like', '%' . $this->buscar . '%');
        })
        ->whereIn('estado',[2,3])
        ->orderBy($this->ordenar_por, $this->direccion)
        ->paginate(6);
        return view('livewire.ventas.listar-ventas-pendientes',compact('ventas'))
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
        //Mostrar los datos de una venta en un modal o cuadro de dialogo
        $this->ventap = $venta;
        $this->cliente=$venta->cliente;
        $this->user=$venta->user;
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
    public function editar(Venta $venta)
    {
        $this->idv=$venta->id;
        $this->nro_venta=$venta->nro_venta;
        $this->fecha=$venta->fecha;
        $this->monto_cancelado=$venta->efectivo;
        $this->total_venta=$venta->total;
        $this->saldo=$venta->total - $venta->efectivo;
        $this->reset(['efectivo','cambio']);
    }
    public function cancelarPago()
    {
        if((float) $this->efectivo <= 0){
            $this->emit('alertaRapida-error','Debe ingresar el monto a cancelar');
            return;
        }
        $venta=Venta::find($this->idv);
        if((float)$this->efectivo >= (float)$this->saldo){
            $venta->estado=1;
            $venta->efectivo=$venta->efectivo+ (float)$this->efectivo;
            $venta->tipo_pago="CANCELADO";
        }else{
            $venta->efectivo=$venta->efectivo+(float)$this->efectivo;
        }
        $venta->save();
        $this->emit('alertaRapida','Pago registrado');
        $this->reset(['cambio','efectivo']);
        
    }
    public function descargarPdf(Venta $venta)
    {
        
        return redirect()->route('pdf',$venta->documento_pdf);
    }
}
