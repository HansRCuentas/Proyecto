<?php

namespace App\Http\Livewire\Ventas;

use App\Models\Cliente;
use Livewire\Component;
use App\Models\Producto;
use App\Models\Venta;
use Livewire\WithPagination;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Barryvdh\DomPDF\Facade\PDF;
class Ventas extends Component
{
    //Para la paginacion
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Para buscar
    public $buscar, $buscar2;
    //Variables para la venta
    public $fecha, $nro_venta, $region, $observacion;
    //Variables de el monto
    public $aumento, $descuento, $efectivo, $total, $cambio;
    //Varibles para el tipo de pago;
    public $tipo_pago, $estado;
    //Variables para el cliente;
    public $idCliente, $cliente, $cedula;
    //Variables para el producto a agregar
    public $idp, $nombrep, $imagenp, $nro_codigop, $stockp, $preciop, $cantidadp;
    //Para el SubTotal
    public $subTotal;
    //Para la factura
    public $indicador=0;
    public $iva;
    //Variable para el numero de venta
    public $ultima_venta;
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
    public function mount()
    {
        $this->resetPage();
        $this->indicador=0;
        $this->idCliente=0;
        $this->ultima_venta=Venta::latest()->first();
        if($this->ultima_venta->nro_venta > 0){
            $this->nro_venta = $this->ultima_venta->nro_venta + 1;
        }else{
            $this->nro_venta = 1;
        }
    
        Cart::clear();
    }

    public function render()
    {
        $productos = Producto::where('tipo', 2)
            ->whereHas('categoria', function ($query) {
                $query->where('nombre', 'like', '%' . $this->buscar . '%');
            })
            ->orwhere('descripcion', 'like', '%' . $this->buscar . '%')
            ->where('tipo', 2)
            ->orWhere('nombre', 'like', '%' . $this->buscar . '%')
            ->where('tipo', 2)
            ->orderBy('nombre', 'asc')
            ->paginate(3);

        $clientes = Cliente::where('nombre', 'like', '%' . $this->buscar2 . '%')
            ->orWhere('region', 'like', '%' . $this->buscar2 . '%')
            ->orWhere('cedula', 'like', '%' . $this->buscar2 . '%')
            ->orderBy('nombre', 'asc')
            ->paginate(3);

        $cart = Cart::getContent()->sortBy('name');

        return view('livewire.ventas.ventas', compact('productos', 'clientes', 'cart'))
            ->extends('adminlte::page')
            ->section('content');
    }
 
    public function agregarCliente(Cliente $clie)
    {
        $this->idCliente = $clie->id;
        $this->cliente = $clie->nombre . ' ' . $clie->cedula;
        $this->cedula = $clie->cedula;
        $this->region = $clie->region; 
        $this->emit('alertaRapida', 'Cliente agregado correctamente');
    }
    public function ajustar()
    {
        if (count(Cart::getContent()) > 0) {
            $this->efectivo = floatval($this->total);
            $this->cambio = 0;
        } else {
            $this->emit('alertaRapida-error', 'Debe agregar productos');
        }
    }
    //Para seleccionar Producto
    public function seleccionarProducto(Producto $prod)
    {
        if ($this->InCart($prod->id)) {
            $this->emit('alertaRapida-error', 'El producto ya fue seleccionado');
            return;
        }
        $this->idp = $prod->id;
        $this->nombrep = $prod->nombre;
        $this->nro_codigop = $prod->nro_codigo;
        $this->reset('cantidadp');
        $this->stockp = $prod->stock;
        $this->preciop = $prod->precio_venta;
    }
    //Para agregar el producto seleccionado al carrito
    public function limpiarProducto()
    {
        $this->reset(['idp', 'nombrep', 'nro_codigop', 'stockp', 'preciop', 'cantidadp']);
    }
    public function agregarCarrito()
    {
        if (!$this->idp) {
            $this->emit('alertaRapida-error', 'Debe seleccionar un producto');
            return;
        }
        if ($this->cantidadp > 0) {
            $producto = Producto::find($this->idp);
            if ($this->InCart($producto->id)) {
                $this->emit('alertaRapida-error', 'El producto ya ha sido agregado');
                return;
            }
            if ($this->cantidadp > $producto->stock) {
                $this->emit('alertaRapida-error', 'El cantidad no debe ser mayor al stock');
                return;
            }
            $rules2 = ['cantidadp' => 'required|numeric|min:0.1'];
            $this->validate($rules2);
            Cart::add(
                array(
                    'id' => $producto->id,
                    'name' => $producto->nombre,
                    'price' => $this->preciop,
                    'quantity' => $this->cantidadp,
                    'attributes' => array(
                        'codigo' => $producto->nro_codigo,
                        'stock' => $producto->stock
                    )
                )
            );
            $this->emit('alertaRapida-success', 'Producto Agregado Correctamente');
            $this->total = Cart::getTotal();
            $this->subTotal=Cart::getTotal();
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
    //Para guardar la venta
    public function guardarVenta()
    {
        // validación
        $rules2 = [
            'region' => 'required',
            'estado' => 'required',
            'cliente' => 'required',
            'idCliente' => 'required'
        ];
        $messages = [
            'region.required' => 'La region es requerida',
            'estado.required' => 'El tipo de pago es requerido',
            'cliente.required' => 'El cliente es requerido',
            'idCliente.required' => 'El cliente es requerido'
        ];
        $this->validate($rules2, $messages);
        if (Cart::isEmpty()) {
            $this->emit('alertaRapida-error', 'Debe agregar productos a la venta');
            return;
        }
        if ((float)$this->efectivo <= 0) {
            $this->emit('alertaRapida-error', 'Debe Ingresar Efectivo');
            return;
        }


        try {
            $venta = new Venta; //................1
            // Se llenan los campos de venta pedido
            $venta->fecha = date("Y-m-d H:i:s"); //................1
            $venta->nro_venta = $this->nro_venta; //................n+1
            $venta->region = $this->region; //................1
            $venta->observacion = $this->observacion; //................1
            $venta->efectivo = $this->efectivo; //................1
            $venta->total = $this->total; //................1
            if ($this->efectivo >= $this->total) { 
                $this->tipo_pago = 'AL CONTADO'; //................1
                $this->estado = 1; //................1
            } else {
                if ($this->estado == 2) {
                    $this->tipo_pago = 'CREDITO'; //................1
                } else {
                    $this->tipo_pago = 'ADELANTO'; //................1
                }
            }

            $venta->tipo_pago = $this->tipo_pago; //................1
            $venta->estado = $this->estado; //................1
            
            if($this->aumento>0){
                $venta->aumento = $this->aumento; //................1
            }else{
                $venta->aumento= 0;
            }
            if($this->descuento>0){
                $venta->descuento = $this->descuento; //................1
            }else{
                $venta->descuento= 0;
            }
            $venta->user_id = Auth()->user()->id; //................1
            $venta->cliente_id = $this->idCliente; //................1
            //           
            $cart = Cart::getContent(); //................n
            $ganancia=0; //................1
            $venta->save(); //................1
            //Varible para registrar la cantidad de una venta
            $cantTotal=0;
            foreach ($cart as $item) {  //................n
                //Producto.save
                $venta->productos()->attach($item->id, ["cantidad" => $item->quantity]); //................1
                //
                $producto = Producto::find($item->id); //................1
                $producto->stock = $producto->stock - $item->quantity;  //................1
                $ganancia=$ganancia+(((float)$item->price-(float)$producto->costo_producto)*$item->quantity); //................3
                //Sumando los productos al Cant Total
                $cantTotal= $cantTotal +$item->quantity;
                //
                $producto->save(); //................1
            } 
            $ganancia=$ganancia+(float)$this->aumento-(float)$this->descuento; //................1
            $venta->ganancia=$ganancia; //................1
            //................1
            //Para generar el comprobante
            $titulo=date('H.i.s').'venta'.date("Y-m-d").'.pdf';
            $venta->documento_pdf=$titulo;
            $venta->cant_prod=$cantTotal;
            $venta->save(); 
            $this->guardarPDF($titulo);
            //Fin de generar Comprobante
            //$this->resetearValores(); 
            //$this->emit('alertaRapida-success', 'La venta se registro correctamente');
        } catch (\Exception $e) {
            $this->emit('alertaRapida-error', 'Ocurrio un error al registrar la venta');
        }
       
    }

    //Para realizar un aumento y un descuento
    public function aumentar()
    {
        if (count(Cart::getContent()) > 0) {
            $this->total = Cart::getTotal() + (float)$this->aumento - (float)$this->descuento;
        } else {
            $this->emit('alertaRapida-error', 'Debe agregar productos');
            $this->reset('aumento');
        }
    }
    //Para realizar un descuento
    public function descontar()
    {
        if (count(Cart::getContent()) > 0) {
            $this->total = Cart::getTotal() - (float)$this->descuento + (float)$this->aumento;
        } else {
            $this->emit('alertaRapida-error', 'Debe agregar productos');
            $this->reset('descuento');
        }
    }
    //Para eliminar productos del carrito
    public function eliminarProdCart($idProd)
    {
        Cart::remove($idProd);

        $this->total = Cart::getTotal();
        $this->subTotal=Cart::getTotal();
        $this->cantProd = Cart::getTotalQuantity();
        //$this->emit('alertaRapida-success-fast', 'Producto eliminado');
  
    }

    //Cancelar la venta y resetear valores
    public function cancelar()
    {

        $this->resetearValores();
    }
    public function resetearValores()
    {
        $this->reset(['idCliente','cliente','cedula','region','observacion',
        'aumento','descuento','total','efectivo','cambio','tipo_pago','estado','subTotal','iva']);
        $this->resetErrorBag(['region','estado','cliente','idCliente']);
        $this->nro_venta+=1;
        $this->indicador=0;
        Cart::clear();
    }
    public function generarPDF()
    {
        //Generar un pdf con una ruta
        $idCliente=$this->idCliente;
        $usuario=Auth()->user()->id;
        $cart = Cart::getContent();

        $pdf = PDF::loadView('documento.pdf',(['idCliente'=>$idCliente,'cart'=>$cart,'usuario'=>$usuario]))->output();
        //$pdf->loadHTML('<h1>Test</h1>');
        return response()->streamDownload(
            fn () => print($pdf),
            "filename.pdf"
       );
        //return view('documento.pdf',compact('productos'));
    }
    public function prepararPDF()
    {
        $idCliente=$this->idCliente;
        $usuario=Auth()->user()->id;
        $cart = Cart::getContent();
        $html=view('documento.pdf', compact('idCliente','usuario','cart'));
        return redirect()->route('generarPDF',$html);
    }
    public function generarPDF2($html)
    {
        // cargar un documento html en el pdf
        $pdf=PDF::loadHTML($html);
        return $pdf->stream();
    }
    public function guardarPDF($titulo)
    {
        //guardar pdf en la carpeta publica y mostrarlo
        
        $cliente=Cliente::find($this->idCliente);
        $usuario=Auth()->user()->name;
        $nro_venta=$this->nro_venta;
        $subtotal=Cart::getTotal();
        if($this->aumento>0){
            $aumento=$this->aumento;
        }else{
            $aumento=0;
        }
        if($this->descuento>0){
            $descuento=$this->descuento;
        }else{
            $descuento=0;
        }
        if($this->iva > 0){
            $iva=$this->iva;
        }else{
            $iva=0;
        }
        $observacion=$this->observacion;
        $cart = Cart::getContent();
        $fecha=strval(date("Y-m-d"));
        $hora=strval(date("H.i.s"));
        if($iva>0){
        $pdf = PDF::loadView('documento.pdf2', 
        compact('cliente','usuario','cart','titulo','aumento','descuento',
        'subtotal','nro_venta','observacion','iva'))
            ->save(public_path('pdfs/') . $titulo);
        }else{
            $pdf = PDF::loadView('documento.pdf', 
            compact('cliente','usuario','cart','titulo','aumento','descuento',
            'subtotal','nro_venta','observacion','iva'))
                ->save(public_path('pdfs/') . $titulo);
        }
       
        $this->resetearValores(); 
        return redirect()->route('pdf',$titulo);
    }
    public function descargarPDF($file)
    {
    
        $path = public_path('pdfs/'.$file);
        // header
       $header = [
         'Content-Type' => 'application/pdf',
         'Content-Disposition' => 'inline; filename="' . $file . '"'
       ];
      return response()->file($path, $header);
    }
    //PAra actualizar la cantidad
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
                    'price' => $this->preciop,
                    'quantity' => $cant,
                    'attributes' => array(
                        'codigo' => $producto->nro_codigo,
                        'stock' => $producto->stock
                    )
                )
            );
            $this->preciop=null;
            $this->total = Cart::getTotal();
            $this->subTotal=Cart::getTotal();
            //$this->emit('scan-ok',$title);
        }

    }
    public function activarIva()
    {
        if($this->indicador==0){
            $this->indicador=1;
            
        }else{
            if($this->indicador==1){
                $this->indicador=0;
                $this->iva=null;
                $this->total=$this->total-$this->iva;
            }
        }
    }
    public function agregarIva()
    {
        if (count(Cart::getContent()) > 0) {
            $this->total = Cart::getTotal() + (float)$this->aumento - (float)$this->descuento + (float)$this->iva;
        } else {
            $this->emit('alertaRapida-error', 'Debe agregar productos');
            $this->reset('iva');
        }
    }
    public function resetearPagina()
    {
        $this->resetPage();
    }
}
