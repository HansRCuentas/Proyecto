<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Venta;
use Carbon\Carbon;

class Pizarra extends Component
{
    public $cantStock,$cantBajo,$cantAgotado;
    //Productos Terminados
    public $cantStockT,$cantBajoT,$cantAgotadoT;
    //Ventas
    public $nroVentas, $totalCantidad, $totalVentas;

    public function mount()
    {
        $this->cantStock=0;
        $this->cantBajo=0;
        $this->cantAgotado=0;
        //Productos Terminados
        $this->cantStockT=0;
        $this->cantBajoT=0;
        $this->cantAgotadoT=0;
        //Variables para las ventas
        $this->nroVentas=0;
        $this->totalCantidad=0;
        $this->totalVentas=0;
    }
    public function render()
    {

          //Para obtener las cantidades de MAteria Prima
          $stock=Producto::where("tipo",1)
          ->whereRaw("stock > stock_minimo")
          ->get();
          $bajo=Producto::where("tipo",1)
          ->whereRaw("stock <= stock_minimo")
          ->whereRaw("stock > 0")
          ->get();
          $agotado=Producto::where("tipo",1)
          ->whereRaw("stock = 0")
          ->get();
          $this->cantStock=count($stock);
          $this->cantBajo=count($bajo);
          $this->cantAgotado=count($agotado);
          $this->cantidadTotalMP=count(Producto::where('tipo',1)->get());
            //Para obtener las cantidades de Productos Terminados
            $stock=Producto::where("tipo",2)
            ->whereRaw("stock > stock_minimo")
            ->get();
            $bajo=Producto::where("tipo",2)
            ->whereRaw("stock <= stock_minimo")
            ->whereRaw("stock > 0")
            ->get();
            $agotado=Producto::where("tipo",2)
            ->whereRaw("stock = 0")
            ->get();
            $this->cantStockT=count($stock);
            $this->cantBajoT=count($bajo);
            $this->cantAgotadoT=count($agotado);
            $this->cantidadTotalPT=count(Producto::where('tipo',2)->get());

            //Para obtener las ventas

            $fi=Carbon::parse(date("Y-m-d H:i:s"))->format('Y-m-d') . ' 00:00:00';
            $ff=Carbon::parse(date("Y-m-d H:i:s"))->format('Y-m-d') . ' 23:59:59';
            $ventas=Venta::whereBetween('fecha',[$fi,$ff])->get();
            $this->nroVentas=count($ventas);
            $this->totalCantidad=0;
            $this->totalVentas=$ventas->sum('total');
            foreach ($ventas as $venta) {
                foreach($venta->productos as $prod){
                    $this->totalCantidad += $prod->pivot->cantidad;
                }
            }
        return view('livewire.dashboard.pizarra')
        ->extends('adminlte::page')
        ->section('content');
    }
}
