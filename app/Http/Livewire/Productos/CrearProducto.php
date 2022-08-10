<?php

namespace App\Http\Livewire\Productos;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Categoria;
use Livewire\WithFileUploads;
class CrearProducto extends Component
{
    use WithFileUploads;
    public $idp, $nombre, $descripcion, $nro_codigo, $stock, $stock_minimo, $costo_producto,$precio_venta,$imagen, $categoria_id,
    $indicador, $disponibilidad;
    public $identificador;
    public function mount()
    {
        $this->identificador=rand();
    }
    public function render()
    {
        $categorias=Categoria::where('tipo',2)->get();
        return view('livewire.productos.crear-producto', compact('categorias'));
    }
    public function guardarProducto()
    {
        $rules2 = [
            'nombre' => "required|min:2|unique:productos,nombre",
            'nro_codigo' => 'required',
            'stock' => 'required|gt:-1',
            'stock_minimo'=>'gt:-1',
            'costo_producto' => 'required|gt:-1',
            'precio_venta'=> 'required|gt:-1',
            'imagen'=>'required|image|max:4096',
            'categoria_id' => 'required'
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
            'precio_venta.required' => 'El precio de venta de producto es requerido',
            'precio_venta.gt'=>'El precio de venta debe ser mayor a 0',
            'imagen.required'=>'La imagen es requerida',
            'imagen.max'=>'El tamaño de la imagen no debe superar los 4Mb',
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
        $prod->precio_venta=$this->precio_venta;
        $imagenCargada=$this->imagen->store('productos');
        $prod->imagen=$imagenCargada;
        $prod->categoria_id=$this->categoria_id;
        $prod->tipo=2;
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
        $this->emitTo('productos.productos','render');
        $this->emit('ocultarCrear','El Producto se creo satisfactoriamente');

    }
    public function resetear()
    {
        $this->identificador=rand();
        $this->reset(['nombre','descripcion','nro_codigo','stock','stock_minimo','costo_producto','precio_venta','imagen','categoria_id']);
        $this->resetErrorBag(['nombre','nro_codigo','stock','stock_minimo','costo_producto','precio_venta','imagen','categoria_id']);
    }
}
