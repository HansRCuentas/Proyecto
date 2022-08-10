<?php

namespace App\Http\Livewire\Reportes;

use Livewire\Component;
use Livewire\WithPagination;
//Modelos que se utilizaran
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\User;
//Carbo
use Carbon\Carbon;

class ReporteVentas extends Component
{
    public $tiempo;
    //Para la paginacion
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Para mostrar los detalles de la venta
    public $venta2,$cliente,$user,$total_medio;
    //Para el boton de consultar
    public $sw=1;
    //Para la lista de ventas
    //Seleccionador
    public $indicador;
    //fechas
    public $desde,$hasta;
    //Totales
    public $totalVentas,$totalGanancia, $totalCantidad;

    public function mount()
    {
        //Constructor que carga los valores al cargar la pagina
        $this->desde=null;
        $this->hasta=null;
        $this->indicador=1;
        //
        $this->venta2=new Venta;
        $this->cliente=new Cliente;
        $this->user=new User;

    }
    public function render()
    {
       // $start_time = microtime(true);
        if( $this->indicador==1){
            $fi=Carbon::parse(date("Y-m-d H:i:s"))->format('Y-m-d') . ' 00:00:00';
            $ff=Carbon::parse(date("Y-m-d H:i:s"))->format('Y-m-d') . ' 23:59:59';
            $ventas=Venta::whereBetween('fecha',[$fi,$ff])
            ->paginate(5);
            $ventas2=Venta::whereBetween('fecha',[$fi,$ff])->get();
            $this->totalCantidad=$ventas->sum('cant_prod');
            $this->totalVentas=$ventas2->sum('total');
            $this->totalGanancia=$ventas2->sum('ganancia');
           
        }
        if($this->indicador==2){
            $fi=Carbon::parse($this->desde)->format('Y-m-d') . ' 00:00:00';
            $ff=Carbon::parse(date($this->hasta))->format('Y-m-d') . ' 23:59:59';
            $ventas=Venta::whereBetween('fecha',[$fi,$ff])
            ->paginate(5);
            $ventas2=Venta::whereBetween('fecha',[$fi,$ff])->get();
            $this->totalCantidad=$ventas->sum('cant_prod');
            $this->totalVentas=$ventas2->sum('total');
            $this->totalGanancia=$ventas2->sum('ganancia');
        }
        //$end_time = microtime(true);
        //$this->tiempo = $end_time - $start_time;
        return view('livewire.reportes.reporte-ventas',compact('ventas'))
        ->extends('adminlte::page')
        ->section('content');
    }
    public function mostrar(Venta $venta)
    {
        //Muestra en un modal los detalles de la venta
        $this->venta2 = $venta;
        $this->cliente=$venta->cliente;
        $this->user=$venta->user;
        $totalm = 0;
        foreach ($venta->productos as $prod) {
            $totalm = $totalm + ($prod->pivot->cantidad * $prod->precio_venta);
        }
        $this->total_medio = $totalm;
    }
    public function consultar()
    {
        //Consulta a la base de datos las ventas realizadas en un rango de fechas.
        if($this->sw==2){
            if($this->desde!=null && $this->hasta!=null){
                $this->indicador=2;
            }else{
                $this->emit('alertaRapida-error','Debe establecer un rango de fechas');
            }
        }else{
            if($this->sw==1){
                $this->indicador=1;
                $this->desde=null;
                $this->hasta=null;
            }
        }
    }
}
