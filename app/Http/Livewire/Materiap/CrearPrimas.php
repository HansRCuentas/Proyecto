<?php

namespace App\Http\Livewire\Materiap;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Categoria;
class CrearPrimas extends Component
{
    public $idp, $nombre, $descripcion, $nro_codigo, $stock, $stock_minimo, $costo_producto, $categoria_id,
    $indicador, $disponibilidad;

    public function mount()
    {
        $this->stock=0;
        $this->stock_minimo=0;
        $this->costo_producto=0;      
    }
    public function render()
    {
        $categorias=Categoria::where('tipo',1)->get();
        return view('livewire.materiap.crear-primas',compact('categorias'));
    }
    public function guardarProducto()
    {
        $rules2 = [
            'nombre' => "required|min:2|unique:productos,nombre",
            'nro_codigo' => 'required',
            'stock' => 'required|gt:-1',
            'stock_minimo'=>'gt:-1',
            'costo_producto' => 'required|gt:-1',
            'categoria_id' => 'required',
        ];
        $messages = [
            'nombre.required' => 'El nombre de producto es requerido',
            'nombre.min' => 'El nombre de producto debe tener al menos 2 caracteres',
            'nombre.unique' => 'El nombre de producto ya existe',
            'nro_codigo.required' => 'El numero de codigo es requerido',
            'stock.required' => 'El stock es requerido',
            'stock.gt'=>'El stock debe ser mayor a 0',
            'stock_minimo.gt'=>'El stock minimo debe ser mayor a 0',
            'costo_producto.required' => 'El costo de producto es requerido',
            'costo_producto.gt'=>'El costo de producto debe ser mayor a 0',
            'categoria_id.required' => 'La categoria es requerida'
        ];
        $this->validate($rules2, $messages);

        $prod=new Producto;
        $prod->nombre=$this->nombre;
        $prod->descripcion=$this->descripcion;
        $prod->nro_codigo=$this->nro_codigo;
        $prod->stock=$this->stock;
        $prod->stock_minimo=$this->stock_minimo;
        $prod->costo_producto=$this->costo_producto;
        $prod->categoria_id=$this->categoria_id;
        $prod->tipo=1;
        if($this->stock > 0){
            if($this->stock < $this->stock_minimo){
                $this->disponibilidad="Stock Bajo";
                $this->indicador=2;
            }else{
                $this->disponibilidad="En Stock";
                $this->indicador=3;
            }
        }else{
            $this->disponibilidad="Agotado";
            $this->indicador=1;
        }
        $prod->disponibilidad=$this->disponibilidad;
        $prod->indicador=$this->indicador;
        $prod->save();
        //Se resetea los campos
        $this->resetear();
        //Se realiza un emit para avisar que se renderize
        $this->emitTo('materiap.primas','render');

        $this->emit('ocultarCrear','El Producto se creo satisfactoriamente');

    }
    public function resetear()
    {
        $this->reset(['nombre','descripcion','nro_codigo','categoria_id']);
        $this->stock=0;
        $this->stock_minimo=0;
        $this->costo_producto=0;
        $this->resetErrorBag(['nombre','nro_codigo','stock','stock_minimo','costo_producto','categoria_id']);
    }
}
