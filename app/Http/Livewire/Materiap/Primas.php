<?php

namespace App\Http\Livewire\Materiap;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Producto;
use App\Models\Categoria;

class Primas extends Component
{
    //Para la paginacion
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Para editar Productos Materia Prima
    public $idp, $nombre, $descripcion, $nro_codigo, $stock, $stock_minimo, $costo_producto, $categoria_id,
        $indicador, $disponibilidad;
    //Para buscar y ordenar los productos
    public $buscar;
    public $direccion = 'asc';
    public $ordenar_por = 'nombre';
    protected $listeners = [
        'render' => 'render',
        'eliminar' => 'eliminar'
    ];

    public function updatingBuscar()
    {
        $this->resetPage();
    }

    public function render()
    {
        $categorias = Categoria::where('tipo', 1)->get();
        $productos = Producto::where('tipo', 1)
            ->Where('nombre', 'like', '%' . $this->buscar . '%')
            ->orwhere('descripcion', 'like', '%' . $this->buscar . '%')
            ->where('tipo', 1)
            ->orderBy($this->ordenar_por, $this->direccion)
            ->paginate(6);
        return view('livewire.materiap.primas',compact('categorias','productos'))
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
            $this->direccion = 'asc';
        }
    }
    public function editar(Producto $prod)
    {
        $this->idp = $prod->id;
        $this->nombre = $prod->nombre;
        $this->descripcion = $prod->descripcion;
        $this->nro_codigo = $prod->nro_codigo;

        $this->stock = $prod->stock;
        $this->stock_minimo = $prod->stock_minimo;
        $this->costo_producto = $prod->costo_producto;
        $this->categoria_id = $prod->categoria_id;
        //Actualizar segun el indicador
        //Actualizar segun el stock o el indicador
        $this->disponibilidad = $prod->disponibilidad;
        $this->indicador = $prod->indicador;
    }
    public function modificar()
    {
        $rules2 = [
            'nombre' => "required|min:2|unique:productos,nombre,$this->idp",
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

        $prod = Producto::find($this->idp);

        $prod->nombre = $this->nombre;
        $prod->descripcion = $this->descripcion;
        $prod->nro_codigo = $this->nro_codigo;
        $prod->stock = $this->stock;
        $prod->stock_minimo = $this->stock_minimo;
        $prod->costo_producto = $this->costo_producto;
        $prod->categoria_id = $this->categoria_id;

        //De acuerdo al stock
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
        $prod->disponibilidad = $this->disponibilidad;
        $prod->indicador = $this->indicador;
        //
        $prod->tipo = 1;
        $prod->save();

        $this->emit('ocultarEditar', 'El producto se modifico satisfactoriamente');
        $this->resetear();
    }
    public function eliminar(Producto $prod)
    {
        $prod->delete();
    }
    public function resetear()
    {
        $this->resetErrorBag(['nombre', 'nro_codigo', 'stock','stock_minimo','costo_producto','categoria_id']);
    }
}
