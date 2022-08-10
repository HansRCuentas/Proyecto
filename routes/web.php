<?php

use App\Http\Controllers\HomeController;
use App\Http\Livewire\Asignar\AsignarPermisos;
use App\Http\Livewire\Catalogo\Caralogo;
use App\Http\Livewire\Catalogo\CatalogoAdmin;
use App\Http\Livewire\Catalogo\CatalogoCategoria;
use App\Http\Livewire\Clientes\Clientes;
use App\Http\Livewire\Compras\ComprasPendientes;
use App\Http\Livewire\Compras\ListaCompras;
use App\Http\Livewire\Compras\RegistrarCompra;
use App\Http\Livewire\Dashboard\Pizarra;
use App\Http\Livewire\Ingresos\ListaIngresos;
use App\Http\Livewire\Ingresos\RegistrarIngreso;
use App\Http\Livewire\Materiap\CategoriaPrima;
use App\Http\Livewire\Materiap\Primas;
use App\Http\Livewire\Nosotros\SobreNosotros;
use App\Http\Livewire\Productos\Productos;
use App\Http\Livewire\Proveedores\CategoriasProveedores;
use App\Http\Livewire\Proveedores\Proveedores;
use App\Http\Livewire\Reportes\ReporteProductos;
use App\Http\Livewire\Reportes\ReporteVentas;
use App\Http\Livewire\Roles\ListaRoles;
use App\Http\Livewire\Salidas\ListaSalidas;
use App\Http\Livewire\Salidas\RegistrarSalida;
use App\Http\Livewire\Terminados\CategoriaTerminados;
use App\Http\Livewire\Usuarios\ListaUsuarios;
use App\Http\Livewire\Ventas\ListaVentas;
use App\Http\Livewire\Ventas\ListarVentasPendientes;
use App\Http\Livewire\Ventas\Ventas;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

   
    Route::get('/admin',Pizarra::class)->name('admin');
    
    //Proveedores
    Route::get('/admin/proveedor/categoria',CategoriasProveedores::class)->name('categoria_proveedor');
    Route::get('/admin/proveedor',Proveedores::class)->name('proveedor');
    //Productos Terminados
    Route::get('/admin/terminados',CategoriaTerminados::class)->name('categoria_terminados');
    //Productos Materia Prima
    Route::get('/admin/prima/categoria',CategoriaPrima::class)->name('categoria_primas');
    Route::get('/admin/prima',Primas::class)->name('primas');
    //Clientes
    Route::get('admin/cliente',Clientes::class)->name('clientes');
    //Productos terminados --> Productos
    Route::get('admin/productos',Productos::class)->name('productos');
    //Para gestionar las ventas
    Route::get('/admin/ventas/registro',Ventas::class)->name('registro_ventas');
    Route::get('/admin/ventas',ListaVentas::class)->name('lista_ventas');
    Route::get('/admin/ventas/pedientes',ListarVentasPendientes::class)->name('ventas_pendientes');
    //Para gestionar ingresos y salidas
    Route::get('/admin/registro/ingreso',RegistrarIngreso::class)->name('registro_ingresos');
    Route::get('/admin/ingresos',ListaIngresos::class)->name('ingresos');
    //Para gestionar salidas de materia prima
    Route::get('/admin/registro/salida',RegistrarSalida::class)->name('registro_salidas');
    Route::get('/admin/salidas',ListaSalidas::class)->name('salidas');
    //Para gestionar compras
    Route::get('/admin/registro/compra',RegistrarCompra::class)->name('registro_compras');
    Route::get('/admin/compras',ListaCompras::class)->name('compras');
    Route::get('/admin/compras/pedientes',ComprasPendientes::class)->name('compras_pendientes');
    //Para gestionar usuarios
    Route::get('/admin/usuarios',ListaUsuarios::class)->name('usuarios');
    //Para gestionar Roles
    Route::get('/admin/roles',ListaRoles::class)->name('roles');
    //Para asignar Roles
    Route::get('/admin/asignar',AsignarPermisos::class)->name('asignar_permisos');
    //Para los reportes
    Route::get('/admin/reporte/ventas',ReporteVentas::class)->name('reporte_ventas');
    //Reporte de Productos
    Route::get('/admin/reporte/productos/{tipo}',ReporteProductos::class)->name('reporte_productos');
    Route::get('/admin/catalogo/administracion',CatalogoAdmin::class)->name('catalogo_admin');
});
Route::get('/catalogo',Caralogo::class)->name('catalogo');

Route::get('/catalogo/categoria/{id}',CatalogoCategoria::class)->name('catalogo_categoria');
Route::get('/generar/PDF/{html}',[Ventas::class,'generarPDF2'])->name('generarPDF');
Route::get('/nosotros',SobreNosotros::class)->name('nosotros');
Route::get('/pdf/{file}', function ($file) {
    // file path
   $path = public_path('pdfs/'.$file);
    // header
   $header = [
     'Content-Type' => 'application/pdf',
     'Content-Disposition' => 'inline; filename="' . $file . '"'
   ];
  return response()->file($path, $header);
})->name('pdf');