<?php

namespace App\Http\Livewire\Salidas;

use Livewire\Component;
use Livewire\WithPagination;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Models\Producto;
use App\Models\Salida;

class RegistrarSalida extends Component
{
    //Para la paginacion
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Para la busqueda
    public $buscar;
    //Variables para la seleccion de producto
    public $idp, $nombrep, $stockp, $cantidadp, $categoriap;
    //Variables para cantidad total
    public $cantidadTotal;
    public function mount()
    {
        Cart::clear();
    }
    public function render()
    {
        //Carga los datos necesarios a la vista
        $productos = Producto::where('tipo', 1)
            ->whereHas('categoria', function ($query) {
                $query->where('nombre', 'like', '%' . $this->buscar . '%');
            })
            ->orwhere('descripcion', 'like', '%' . $this->buscar . '%')
            ->where('tipo', 1)
            ->orWhere('nombre', 'like', '%' . $this->buscar . '%')
            ->where('tipo', 1)
            ->orderBy('nombre', 'asc')
            ->paginate(4);

        $cart = Cart::getContent()->sortBy('name');
        return view('livewire.salidas.registrar-salida',compact('productos','cart'))
        ->extends('adminlte::page')
        ->section('content');
    }
    public function seleccionarProducto(Producto $prod)
    {
        //Para seleccionar un producto y agregar su cantidad 
        if ($this->InCart($prod->id)) {
            $this->emit('alertaRapida-error', 'El producto ya se agrego');
            return;
        }
        $this->idp = $prod->id;
        $this->nombrep = $prod->nombre;
        $this->stockp = $prod->stock;
        $this->categoriap = $prod->categoria->nombre;

        $this->emit('alertaRapida-success', 'Producto Seleccionado');
    }
    public function agregarCarrito()
    {
        //Para agregar un producto a la salida
        if (!$this->idp) {
            $this->emit('alertaRapida-error', 'Debe seleccionar un producto');
            return;
        }
        if ($this->cantidadp > 0) {
            
            $producto = Producto::find($this->idp);
            if($this->cantidadp > $producto->stock){
                $this->emit('alertaRapida-error', 'La cantidad no debe ser mayor al stock');
                return;
            }
            if ($this->InCart($producto->id)) {
                $this->emit('alertaRapida-error', 'El producto ya ha sido agregado');
                return;
            }
            $rules2 = ['cantidadp' => 'required|numeric|min:0.1',
                        'idp'=>'required'];
            $this->validate($rules2);
            Cart::add(
                array(
                    'id' => $producto->id,
                    'name' => $producto->nombre,
                    'price' => $producto->precio_venta,
                    'quantity' => $this->cantidadp,
                    'attributes' => array(
                        'codigo' => $producto->nro_codigo,
                        'stock' => $producto->stock,
                        'categoria' => $producto->categoria->nombre
                    )
                )
            );
            $this->emit('alertaRapida-success', 'Producto Agregado Correctamente');
            $this->cantidadTotal = Cart::getTotalQuantity();
            $this->limpiarProducto();
        } else {
            $this->emit('alertaRapida-error', 'Debe seleccionar la Cantidad');
            return;
        }
    }
    public function InCart($idProd)
    {
        $exist = Cart::get($idProd);
        if ($exist) {
            return true;
        } else {
            return false;
        }
    }
    public function limpiarProducto()
    {
        $this->reset(['idp', 'nombrep', 'stockp', 'cantidadp', 'categoriap']);
    }
    public function guardarSalida()
    {
        if (Cart::isEmpty()) {
            $this->emit('alertaRapida-error', 'Debe agregar productos a la venta');
            return;
        }
        try {
            $salida = new Salida;
            $salida->fecha = date("Y-m-d H:i:s");
            $salida->user_id = Auth()->user()->id;
            $salida->save();
            $cart = Cart::getContent();
            foreach ($cart as $item) {
                //Producto.save
                $salida->productos()->attach($item->id, ["cantidad" => $item->quantity]);
                //
                $producto = Producto::find($item->id);
                $producto->stock = $producto->stock - $item->quantity;
                $producto->save();
            }
            $this->reset('cantidadTotal');
            Cart::clear();
            $this->emit('alertaRapida-success', 'La salida se registro correctamente');
        } catch (\Exception $e) {
            $this->emit('alertaRapida-error', 'Ocurrio un error al guardar la salida');
        }
    }
    public function eliminarProdCart($idProd)
    {
        Cart::remove($idProd);
        $this->cantidadTotal = cart::getTotalQuantity();
        //$this->emit('alertaRapida-success', 'Producto eliminado');
    }
    public function cancelar()
    {
        Cart::clear();
    }
    public function actualizarCant($idProd,$cant)
    {
        
        $title='';
        $producto=Producto::find($idProd);
        $exist= Cart::get($idProd);
        if($exist)
            $title='Cantidad actualizada';
        else
            $title="Producto agregado";

        if($exist){
            if($producto->stock < $cant){
                $this->emit('alertaRapida-error','La cantidad es mayor al Stock');
                return;
            }
        }
        $this->preciop=$exist->price;
        $this->eliminarProdCart($idProd);
        if($cant>0){
           
            Cart::add(
                array(
                    'id' => $producto->id,
                    'name' => $producto->nombre,
                    'price' => $producto->precio_venta,
                    'quantity' => $cant,
                    'attributes' => array(
                        'codigo' => $producto->nro_codigo,
                        'stock' => $producto->stock,
                        'categoria' => $producto->categoria->nombre
                    )
                )
            );
            //$this->emit('alertaRapida-success', 'Cantidad Actualizada');
            $this->cantidadTotal = Cart::getTotalQuantity();
            $this->limpiarProducto();
            
        }

    }
}
