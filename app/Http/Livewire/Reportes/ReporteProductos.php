<?php

namespace App\Http\Livewire\Reportes;

use Livewire\Component;
use Livewire\WithPagination;
//Modelos
use App\Models\Producto;
use DB;

class ReporteProductos extends Component
{
    //Para la paginacion
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Para seleccionar el tipo de productos
    public $tipo,$estado;
    //Cantidades
    public $cantStock,$cantAgotado,$cantBajo;
    protected $listeners=['cambiarTipo'];
    public function mount($tipo)
    {
        $this->cantStock=0;
        $this->cantAgotado=0;
        $this->cantBajo=0;
        $this->tipo=$tipo;
        $this->estado=0;
    }
   
    public function render()
    {
        //El estado representa el stock, si es en stock bajo stock, o agotado.
        if($this->estado==0){
            $productos=Producto::where("tipo",$this->tipo)->paginate(5);
            //Para obtener las cantidades

        }
        if($this->estado==1){
            $productos=Producto::where('tipo',$this->tipo)
            ->whereRaw("stock > stock_minimo")
            ->paginate(5);
        }
        if($this->estado==2){
            $productos=Producto::where('tipo',$this->tipo)
            ->whereRaw("stock <= stock_minimo")
            ->whereRaw("stock > 0")
            ->paginate(5);
        }
        if($this->estado==3){
            $productos=Producto::where('tipo',$this->tipo)
            ->whereRaw("stock = 0")
            ->paginate(5);
        }
        //Para obtener las cantidades
        $stock=Producto::where("tipo",$this->tipo)
        ->whereRaw("stock > stock_minimo")
        ->get();
        $bajo=Producto::where("tipo",$this->tipo)
        ->whereRaw("stock <= stock_minimo")
        ->whereRaw("stock > 0")
        ->get();
        $agotado=Producto::where("tipo",$this->tipo)
        ->whereRaw("stock = 0")
        ->get();
        $this->cantStock=count($stock);
        $this->cantBajo=count($bajo);
        $this->cantAgotado=count($agotado);
        
        return view('livewire.reportes.reporte-productos', compact('productos'))
            ->extends('adminlte::page')
            ->section('content');
    }
    public function cambiarTipo($tipo)
    {
        $this->tipo=$tipo;
    }
}
