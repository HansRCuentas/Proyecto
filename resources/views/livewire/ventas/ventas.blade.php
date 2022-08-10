<div>
    @section('title', 'Registro Venta')
    <h1><b>Registro de Venta</b></h1>

    <div class="card">
        <div class="card-body">

            <div class="form-row">
                <div class="form-group form-inline col-md-4">
                    <label for="nro">NRO DE VENTA</label>
                    <input type="number" class="form-control ml-3 text-center" id="nro" readonly wire:model.defer='nro_venta'>
                </div>

            </div>

            <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="cliente">CLIENTE</label>
                    <div class="form-row">
                        <div class="form-group col-md-10">
                            <input type="text" class="form-control" placeholder="Busca un cliente" readonly wire:model.defer='cliente'>
                        </div>
                        <div class="form-group col-md-2">
                            <button class="btn btn-danger form-control" data-toggle="modal" data-target="#modalClientes" wire:click="resetearPagina"><i class="fa-solid fa-magnifying-glass"></i>Buscar</button>

                        </div>
                    </div>
                    @error('cliente')
                    <small class="form-text text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="region">REGIÓN</label>
                    <select class="form-control" wire:model.defer="region" disabled>
                        <option selected value="">Selecciona la region</option>
                        <option value="LA PAZ">LA PAZ</option>
                        <option value="EL ALTO">EL ALTO</option>
                        <option value="ORURO">ORURO</option>
                        <option value="POTOSI">POTOSI</option>
                        <option value="CHUQUISACA">CHUQUISACA</option>
                        <option value="TARIJA">TARIJA</option>
                        <option value="BENI">BENI</option>
                        <option value="PANDO">PANDO</option>
                        <option value="SANTA CRUZ">SANTA CRUZ</option>
                    </select>
                    @error('region')
                    <small class="form-text text-danger">{{$message}}</small>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <button class="form-group btn btn-info" data-toggle="modal" data-target="#modalProductos" wire:click="resetearPagina"><i class="fa-solid fa-magnifying-glass"></i>Buscar Productos</button>
            </div>
            <!-- TAAAAABLAAAAAAAAAAA -->
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="thead-dark text-center align-middle">
                        <tr>
                            <th scope="col">NRO_CODIGO</th>
                            <th scope="col" width="300">NOMBRE</th>
                            <th scope="col">STOCK</th>
                            <th scope="col">CANTIDAD</th>
                            <th scope="col">PRECIO</th>
                            <th scope="col">SUB_TOTAL</th>
                            <th scope="col">ACCIONES</th>
                        </tr>
                    </thead>
                    @if (count($cart))
                    <tbody>
                        @foreach ($cart as $item)
                        <tr>
                            <td>{{$item->attributes->codigo}} </td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->attributes->stock}} </td>
                            <td>
                                <input class="form-control" type="number" id="r{{$item->id}}" wire:change.prevent="actualizarCant({{$item->id}}, $('#r' + {{$item->id}}).val() )" value="{{$item->quantity}}">
                            </td>
                            <td>
                                <input class="form-control" type="number" value="{{$item->price}}" readonly>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" class="form-control bg-secondary" value="{{$item->quantity*$item->price}}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Bs.</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row d-block">
                                    <!-- Boton para eliminar del carrito-->
                                    <a class="btn btn-danger" data-toggle="modal" data-target="#modalEditar" wire:click="eliminarProdCart({{$item->id}})"><i class="fa-solid fa-trash"></i> </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><strong>SUB. TOTAL</strong></td>
                            <td>

                                <div class="input-group">
                                    <input type="text" class="form-control bg-secondary" value="{{$subTotal}}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Bs.</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    @endif

                </table>
            </div>
            <!-- FIN DA LA TABLA RESPONSIVA -->
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="observacion">OBSERVACIÓN</label>
                    <textarea name="" class="form-control" id="observacion" wire:model.defer='observacion'></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="aumento">AUMENTO</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="aumento" placeholder="0" wire:model='aumento' wire:change="aumentar">
                        <div class="input-group-append">
                            <span class="input-group-text">Bs.</span>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label for="descuento">DESCUENTO</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="descuento" placeholder="0" wire:model='descuento' wire:change="descontar">
                        <div class="input-group-append">
                            <span class="input-group-text">Bs.</span>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label for="pago">TIPO DE PAGO</label>
                    <select class="form-control" wire:model.defer='estado'>
                        <option value="" selected>--Seleccione una opcion--</option>
                        <option value="1">AL CONTADO</option>
                        <option value="2">CREDITO</option>
                        <option value="3">ADELANTO</option>
                    </select>
                    @error('estado')
                    <small class="form-text text-danger">{{$message}}</small>
                    @enderror
                </div>
            </div>
            <div class="form-row justify-content-center">
                <div class="form-group col-md-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="ivas" wire:change="activarIva" {{$indicador == 1 ? 'checked' : 'unchecked'}}>
                        <label class="form-check-label" for="ivas">
                            <strong>IVA - 13%</strong>
                        </label>

                    </div>
                    <div class="input-group">
                        <input type="number" class="form-control" placeholder="0" wire:model='iva' wire:change="agregarIva" {{$indicador == 0 ? 'disabled' : ''}}>
                        <div class="input-group-append">
                            <span class="input-group-text">Bs.</span>
                        </div>
                    </div>

                </div>
                <div class="form-group col-md-3">
                    <label for="monto">MONTO CANCELADO</label>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <button class="btn btn-danger form-control" wire:click='ajustar'>Ajustar</button>
                        </div>
                        <div class="form-group col-md-8">
                            <div class="input-group">
                                <input type="number" class="form-control" id="monto" placeholder="0" wire:model='efectivo'>
                                <div class="input-group-append">
                                    <span class="input-group-text">Bs.</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="form-group col-md-3">
                    <label for="cancelado">CAMBIO</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="cancelado" placeholder="0" wire:model='cambio' readonly>
                        <div class="input-group-append">
                            <span class="input-group-text">Bs.</span>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-3">
                    <label for="cancelado">TOTAL</label>
                    <div class="input-group">
                        <input type="text" class="form-control bg-success" placeholder="0" wire:model='total' readonly>
                        <div class="input-group-append">
                            <span class="input-group-text">Bs.</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row justify-content-center">

                <button class="btn btn-success ml-2" wire:click="guardarVenta">Registrar Venta</button>
                <a class="btn btn-info ml-2" wire:click="guardarPDF">Guardar PDF</a>
                <button class="btn btn-danger ml-2" wire:click="cancelar">Cancelar</button>
            </div>

        </div>

        <!-- Modal Para Mostrar productos -->
        <div wire:ignore.self class="modal fade" id="modalProductos" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Lista de Productos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="limpiarProducto">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div wire:loading wire:target="resetearPagina" class="text-center text-primary">
                        <div class="spinner-border" role="status">
                        </div>
                    </div>
                    <div class="modal-body container" wire:loading.remove wire:target="resetearPagina">
                        <!--  PRODUCTO SELECCIONADO -->
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="nombrep">Nombre</label>
                                <input type="text" class="form-control " id="nombre" wire:model.defer='nombrep' readonly>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="cantidadp">Cantidad</label>
                                <input type="number" class="form-control " id="cantidad" placeholder="0" wire:model.defer='cantidadp'>

                            </div>
                            <div class="form-group col-md-3">
                                <label for="preciop">Precio de Venta</label>
                                <input type="number" class="form-control " id="preciop" placeholder="0" wire:model.defer='preciop'>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="stockp">Stock</label>
                                <input type="number" class="form-control " id="stockp" wire:model.defer='stockp' readonly>

                            </div>


                        </div>

                        <div class="form-row">
                            <button class="form-group btn btn-info" wire:click="agregarCarrito">Agregar</button>
                        </div>
                        <!--  FIN DE PRODUCTO SELECCIONADO -->
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="observacion">BUSCAR:</label>
                                <input type="text" class="form-control" id="buscar" placeholder="Ingresa el texto a buscar" wire:model="buscar">
                            </div>
                        </div>
                        @if (count($productos))
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered">
                                <thead class="thead-dark text-center align-middle">
                                    <tr>
                                        <th scope="col">Seleccione</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Descripcion</th>
                                        <th scope="col">Nro_codigo</th>
                                        <th scope="col">Stock</th>
                                        <th scope="col">Precio</th>
                                        <th scope="col">Categoria</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productos as $prod)
                                    <tr>
                                        <th>
                                            @if ($prod->stock>0)
                                            <a class="btn btn-success" wire:click="seleccionarProducto({{$prod->id}})"><i class="fa-solid fa-check"></i></a>
                                            @else
                                            <a class="btn btn-success"><i class="fa-solid fa-ban"></i></a>

                                            @endif
                                        </th>
                                        <td>{{$prod->nombre}}</td>
                                        <td>{{$prod->descripcion}}
                                        </td>
                                        <td>{{$prod->nro_codigo}}</td>
                                        <td>{{$prod->stock}}</td>
                                        <td width="150px">
                                            {{$prod->precio_venta." Bs."}}
                                            <dl>
                                            @foreach ($prod->precios as $precio)
                                            <li> {{$precio->cantidad}}u.-{{$precio->precio}}Bs.</li>
                                            @endforeach
                                            </dl>
                                        </td>
                                        <td>{{$prod->categoria->nombre}}</td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        @if ($productos->hasPages())

                        <div class="row justify-content-center">
                            {{$productos->links()}}
                        </div>

                        @endif
                        @else
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <h3>No se encontraron registros</h3>
                            </div>
                        </div>
                        @endif

                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="limpiarProducto">Cerrar</button>

                    </div>

                </div>
            </div>
        </div>

        <!-- Modal Para Mostrar Clientes -->
        <div wire:ignore.self class="modal fade" id="modalClientes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Lista de Clientes</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div wire:loading wire:target="resetearPagina" class="text-center text-primary">
                        <div class="spinner-border" role="status">
                        </div>
                    </div>
                    <div class="modal-body container" wire:loading.remove wire:target="resetearPagina">

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="observacion">BUSCAR:</label>
                                <input type="text" class="form-control" id="buscar" placeholder="Ingresa el texto a buscar" wire:model="buscar2">
                            </div>
                        </div>
                        @if (count($clientes))
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered">
                                <thead class="thead-dark text-center align-middle">
                                    <tr>
                                        <th role="button" scope="col">Seleccione</th>
                                        <th role="button" scope="col">Nombre</th>
                                        <th role="button" scope="col">Celular</th>
                                        <th role="button" scope="col">Direccion</th>
                                        <th role="button" scope="col">Region</th>
                                        <th role="button" scope="col">Cedula</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($clientes as $clie)
                                    <tr>
                                        <th>
                                            <a class="btn btn-success" wire:click="agregarCliente({{$clie->id}})"><i class="fa-solid fa-check"></i></a>
                                        </th>
                                        <td>{{$clie->nombre}}</td>
                                        <td>{{$clie->celular}}
                                        </td>
                                        <td>{{$clie->direccion}}</td>
                                        <td>{{$clie->region}}</td>
                                        <td>{{$clie->cedula}}</td>

                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        @if ($clientes->hasPages())

                        <div class="row justify-content-center">
                            {{$clientes->links()}}
                        </div>

                        @endif
                        @else
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <h3>No se encontraron registros</h3>
                            </div>
                        </div>
                        @endif

                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

                    </div>

                </div>
            </div>
        </div>

        @section('js')

        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Livewire.on('mostrarToast', (message) => {

                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: 'Producto Agregado!!!' + message,
                    showConfirmButton: false,
                    timer: 1000
                })
            });
        </script>
        <script>
            Livewire.on('alertaRapida', (message) => {

                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: message,
                    showConfirmButton: false,
                    timer: 1500
                })
                $('#modalClientes').modal('hide')
            });
        </script>
        <script>
            Livewire.on('alertaRapida-error', (message) => {

                Swal.fire({
                    position: 'top',
                    icon: 'error',
                    title: message,
                    showConfirmButton: true,

                })
            });
            Livewire.on('alertaRapida-success', (message) => {

                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: message,
                    showConfirmButton: false,
                    timer: 1500
                })
            });
        </script>

        @stop
    </div>


</div>

</div>