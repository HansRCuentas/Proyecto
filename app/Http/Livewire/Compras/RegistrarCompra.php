<?php

namespace App\Http\Livewire\Compras;

use Livewire\Component;
//Modelos
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Compra;
//Otros
use Livewire\WithPagination;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class RegistrarCompra extends Component
{
    //Para la paginacion
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //variables para seleccionar el producto
    public $idp,$nombrep,$nro_codigop,$precio_comprap,$stockp,$cantidadp;
    //Para el total de la ventas
    public $total;
    //Para saleccionar al proveedor
    public $idProv,$datosProv;
    //Para buscar los productos
    public $buscar,$buscar2;
    //Para el tipo de pago en compras
    public $tipo_pago;
    public $efectivo;
    public $cambio;
    //Variable para probar el nro de compra
    public $ultima_compra;
    public function mount()
    {
        $this->ultima_compra=Compra::latest()->first();
        if($this->ultima_compra->nro_compra > 0){
            $this->nro_compra = $this->ultima_compra->nro_compra + 1;
        }else{
            $this->nro_compra = 1;
        }
       
        Cart::clear();
    }
    public function updatingBuscar()
    {
        $this->resetPage();
    }
    public function updatingBuscar2()
    {
        $this->resetPage();
    }
    public function updatedEfectivo()
    {
        $efectivo = floatval($this->efectivo);
        $total = floatval($this->total);
        if (count(Cart::getContent()) > 0) {
            if ($efectivo >= $total) {
                $this->cambio = $efectivo - $total;
            } else {

                $this->cambio = 0;
                return;
            }
        } else {
            $this->emit('alertaRapida-error', 'Debe seleccionar productos');
            $this->reset('efectivo');
        }
    }
    public function render()
    {
        $productos = Producto::where('tipo', 1)
            ->whereHas('categoria', function ($query) {
                $query->where('nombre', 'like', '%' . $this->buscar . '%');
            })
            ->orwhere('descripcion', 'like', '%' . $this->buscar . '%')
            ->where('tipo', 1)
            ->orwhere('nro_codigo', 'like', '%' . $this->buscar . '%')
            ->where('tipo', 1)
            ->orWhere('nombre', 'like', '%' . $this->buscar . '%')
            ->where('tipo', 1)
            ->orderBy('nombre', 'asc')
            ->paginate(3);

        $proveedores=Proveedor::whereHas('categoria', function ($query) {
            $query->where('nombre', 'like', '%' . $this->buscar2 . '%');
        })
        ->orwhere('empresa', 'like', '%' . $this->buscar2 . '%')
        ->orWhere('celular', 'like', '%' . $this->buscar2 . '%')
        ->orWhere('nit', 'like', '%' . $this->buscar2 . '%')
        ->orderBy('empresa', 'asc')
        ->paginate(5);

        $cart = Cart::getContent()->sortBy('name');

        return view('livewire.compras.registrar-compra',compact('productos','proveedores','cart'))
        ->extends('adminlte::page')
        ->section('content');
    }
    public function agregarProveedor(Proveedor $prov)
    {
        //Agrega un proveedor a la venta
        $this->idProv=$prov->id;
        $this->datosProv= $prov->empresa . ' ' . $prov->celular;
        $this->emit('alertaRapida-success-modal','El proveedor se registro correctamente');
    }
    public function seleccionarProducto(Producto $prod){
        //Se selecciona un producto para gregar a la compra con su cantidad
        if ($this->InCart($prod->id)) {
            $this->emit('alertaRapida-error', 'El producto ya ha sido agregado');
            return;
        }
        $this->idp=$prod->id;
        $this->nro_codigop=$prod->nro_codigo;
        $this->nombrep=$prod->nombre;
        //La cantidad se resetea por si ya tiene un valor
        $this->reset('cantidadp');
        $this->stockp=$prod->stock;
        $this->precio_comprap=$prod->costo_producto;
        $this->emit('alertaRapida-success-fast','');
    }
    public function limpiarProducto()
    {
        $this->reset(['idp','nro_codigop','nombrep','stockp','cantidadp','precio_comprap']);
    }
    //Para agregar al carrito
    public function agregarCarrito()
    {
        if (!$this->idp) {
            $this->emit('alertaRapida-error', 'Debe seleccionar un producto');
            return;
        }
        if ($this->cantidadp > 0) {
            $producto = Producto::find($this->idp);
            $rules2 = ['cantidadp' => 'required|numeric|min:0.1',
                        'precio_comprap'=>'required|numeric'];
            $this->validate($rules2);
            Cart::add(
                array(
                    'id' => $producto->id,
                    'name' => $producto->nombre,
                    'price' => $this->precio_comprap,
                    'quantity' => $this->cantidadp,
                    'attributes' => array(
                        'codigo' => $producto->nro_codigo,
                        'stock' => $producto->stock
                    )
                )
            );
            $this->emit('alertaRapida-success', 'Producto Agregado Correctamente');
            $this->total = Cart::getTotal();
            $this->limpiarProducto();
        } else {
            $this->emit('alertaRapida-error', 'Debe Ingresar una Cantidad');
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
    //Para guardar la Compra

    public function guardarCompra()
    {
        //Validacion
        $rules2=[
            'idProv'=>'required',
            'tipo_pago'=>'required',
            'efectivo'=>'required'
        ];
        $messages=['idProv.required'=>'El proveedor es requerido',
                    'tipo_pago.required'=>'Debe seleccionar un tipo de pago',
                    'efectivo.required'=>'Debe ingresar un efectivo'];
        $this->validate($rules2,$messages);
        if (Cart::isEmpty()) {
            $this->emit('alertaRapida-error', 'Debe agregar productos a la compra');
            return;
        }
        try {
            $compra = new Compra;
            $compra->fecha = date("Y-m-d H:i:s");
            $compra->total=$this->total;
            $compra->user_id=Auth()->user()->id;
            $compra->proveedor_id=$this->idProv;
            $compra->nro_compra=$this->nro_compra;
            //Se actualiza el nro_compra
            $this->nro_compra+=1;
            //Para registrar con tipo de pago
            if($this->efectivo>=$this->total){
                $this->tipo_pago=1;
            }
            $compra->tipo_pago=$this->tipo_pago;
            $compra->efectivo=$this->efectivo;
            //Se guarda la compra
            $compra->save();
            //Se obtiene los productos del carrito
            $cart=Cart::getContent();
            foreach ($cart as $item) {
                //Producto.save
                $compra->productos()->attach($item->id, ["cantidad" => $item->quantity]);
                //
                $producto = Producto::find($item->id);
                $producto->stock = $producto->stock + $item->quantity;
                $producto->costo_producto=(float)$item->price;
                $producto->save();
            }
            $this->cancelar();
            $this->emit('alertaRapida-success','La compra se registro correctamente');
        } catch (\Exception $e) {
            $this->emit('alertaRapida-error', 'Ocurrio un error al registrar la compra');

        }
    }
    //Para eliminar del carrito
    public function eliminarProdCart($idProd)
    {
        Cart::remove($idProd);
        $this->total = Cart::getTotal();
        //$this->emit('alertaRapida-success-fast', 'Producto eliminado');
    }
    //Para cancelar la Transaccion
    public function cancelar()
    {
        $this->reset(['idProv','datosProv','total','tipo_pago','efectivo','cambio']);
        $this->resetValidation();
        Cart::clear();
    }
    //Para actualizar la cantidad
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
        $this->precio_comprap=$exist->price;
        $this->eliminarProdCart($idProd);
        if($cant>0){
            Cart::add(
                array(
                    'id' => $producto->id,
                    'name' => $producto->nombre,
                    'price' => $this->precio_comprap,
                    'quantity' => $cant,
                    'attributes' => array(
                        'codigo' => $producto->nro_codigo,
                        'stock' => $producto->stock
                    )
                )
            );
            $this->precio_comprap=null;
            $this->total = Cart::getTotal();
            //$this->emit('scan-ok',$title);
        }

    }
    //Para registrar compras con un tipo de pago
    public function ajustar()
    {
        if (count(Cart::getContent()) > 0) {
            $this->efectivo = floatval($this->total);
            $this->cambio = 0;
        } else {
            $this->emit('alertaRapida-error', 'Debe agregar productos');
        }
    }
    public function resetearPagina()
    {
        $this->resetPage();
    }
}
