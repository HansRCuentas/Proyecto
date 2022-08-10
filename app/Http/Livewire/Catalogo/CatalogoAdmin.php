<?php

namespace App\Http\Livewire\Catalogo;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\Producto;
use App\Models\Precio;
use App\Models\Categoria;

class CatalogoAdmin extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Para la busqueda
    public $buscar;
    //Variables para mostrar el precio
    public $idprod, $nombre_prod, $precio_unidad;
    public $precios = [];
    //Variables para agregar un precio
    public $precio, $cantidad;
    //Para editar el Producto
    public $idp, $nombre, $descripcion, $precio_venta, $categoria_id, $imagen;
    //Para la nueva imagen
    public $imagen2, $identificador;
    public function mount()
    {
        $identificador = rand();
    }
    public function render()
    {
        $productos = Producto::where('tipo', 2)
            ->whereHas('categoria', function ($query) {
                $query->where('nombre', 'like', '%' . $this->buscar . '%');
            })
            ->orWhere('nombre', 'like', '%' . $this->buscar . '%')
            ->where('tipo', 2)
            ->orwhere('descripcion', 'like', '%' . $this->buscar . '%')
            ->where('tipo', 2)


            ->orderBy('nombre', 'desc')
            ->paginate(6);
        $categorias = Categoria::where('tipo', 2)->get();
        return view('livewire.catalogo.catalogo-admin', compact('productos', 'categorias'))
            ->extends('adminlte::page')->section('content');
    }
    public function editar(Producto $prod)
    {
        $this->idp = $prod->id;
        $this->nombre = $prod->nombre;
        $this->descripcion = $prod->descripcion;
        $this->precio_venta = $prod->precio_venta;
        $this->categoria_id = $prod->categoria_id;
        $this->imagen = $prod->imagen;
        $this->imagen2=null;
        $this->identificador = rand();
    }
    public function modificar()
    {
        $rules2 = [
            'nombre' => "required|min:2|unique:productos,nombre,$this->idp",
            'precio_venta' => 'required|gt:-1',
            'categoria_id' => 'required'
        ];
        $messages=[
            'nombre.required' => 'El nombre de producto es requerido',
            'nombre.min' => 'El nombre de producto debe tener al menos 2 caracteres',
            'nombre.unique' => 'El nombre de producto ya existe',     
            'precio_venta.required' => 'El precio de venta de producto es requerido',
            'precio_venta.gt'=>'El precio de venta debe ser mayor a 0',
            'categoria_id.required' => 'La categoria es requerida'
        ];
        $this->validate($rules2,$messages);
        $prod=Producto::find($this->idp);
        $prod->nombre=$this->nombre;
        $prod->descripcion=$this->descripcion;
        $prod->precio_venta=$this->precio_venta;
        $prod->categoria_id=$this->categoria_id;
        if($this->imagen2){
            Storage::delete([$prod->imagen]);
            $prod->imagen=$this->imagen2->store('productos');
        }
        $prod->save();
        $this->emit('ocultarEditar','El producto se modifico satisfactoriamente');
    }
    public function agregarPrecios(Producto $prod)
    {
        $this->idprod = $prod->id;
        $this->nombre_prod = $prod->nombre;
        $this->precio_unidad = $prod->precio_venta;
        $this->precios = $prod->precios;
    }
    public function guardarPrecio($idp)
    {
        $rules2 = [
            'precio' => 'required',
            'cantidad' => 'required'
        ];
        $messages = [
            'precio.required' => 'El precio es requerido',
            'cantidad.required' => 'La cantidad es requerida'
        ];
        $this->validate($rules2, $messages);
        $pre = new Precio;
        $pre->precio = $this->precio;
        $pre->cantidad = $this->cantidad;
        $pre->producto_id = $idp;
        $pre->save();

        $prod = Producto::find($idp);
        $this->precios = $prod->precios;
        $this->emit('alertaRapida-success', 'Precio agregado correctamente');
        $this->reset(['cantidad', 'precio']);
        $this->resetValidation();
    }
    public function eliminarPrecio(Precio $pre)
    {
        $prodId = $pre->producto->id;
        $pre->delete();
        $prod = Producto::find($prodId);
        $this->precios = $prod->precios;
    }
    public function agregarPagina(Producto $prod)
    {
        if ($prod->indicador == 1) {
            $prod->indicador = 0;
            $this->emit('alertaRapida-success', 'Producto quitado de la Pagina');
        } else {
            $prod->indicador = 1;
            $this->emit('alertaRapida-success', 'Producto agregado a la pagina');
        }
        $prod->save();
    }
}
