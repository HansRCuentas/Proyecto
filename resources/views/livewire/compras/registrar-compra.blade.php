<div>
    @section('title', 'Registro Compra')
    <h1><b>Registro de Compra de Materia Prima</b></h1>

    <div class="card">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="nro">NRO DE COMPRA</label>
                    <input type="number" class="form-control  text-center" id="nro" readonly wire:model.defer='nro_compra'>
                </div>
                <div class="form-group col-md-3">
                    <label for="fecha">FECHA</label>
                    <input type="text" class="form-control  text-center" id="nro" readonly value="{{date('Y-m-d')}}">
                </div>

                <div class="form-group col-md-7">
                    <label for="cliente">PROVEEDOR</label>
                    <div class="form-row">
                        <div class="form-group col-md-9">
                            <input type="text" class="form-control" placeholder="Busca un Proveedor" readonly wire:model.defer='datosProv'>
                        </div>
                        <div class="form-group col-md-3">
                            <button class="btn btn-danger form-control" data-toggle="modal" data-target="#modalProveedores" wire:click="resetearPagina"><i class="fa-solid fa-magnifying-glass"></i>Buscar</button>

                        </div>
                    </div>
                    @error('idProv')
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
                            <th scope="col">NOMBRE</th>
                            <th scope="col">STOCK</th>
                            <th scope="col">CANTIDAD</th>
                            <th scope="col">PRECIO_COMPRA</th>
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
                                <input class="form-control bg-secondary" type="number" value="{{$item->quantity*$item->price}}" readonly>
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
                            <td><strong>PRECIO TOTAL:</strong></td>
                            <td>
                                <input class="form-control bg-secondary" type="number" value="{{$total}}" readonly>

                            </td>
                        </tr>
                    </tbody>
                    @endif

                </table>
            </div>
            <!-- FIN DA LA TABLA RESPONSIVA -->


            <div class="form-row justify-content-center">
                <div class="form-group col-md-3">
                    <label for="pago">TIPO DE PAGO</label>
                    <select class="form-control" wire:model.defer='tipo_pago'>
                        <option value="" selected>--Seleccione una opcion--</option>
                        <option value="1">AL CONTADO</option>
                        <option value="2">CREDITO</option>
                        <option value="3">ADELANTO</option>
                    </select>
                    @error('tipo_pago')
                    <small class="form-text text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group col-md-3">
                    <label for="monto">EFECTIVO CANCELADO</label>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <button class="btn btn-danger form-control" wire:click='ajustar'>Ajustar</button>
                        </div>
                        <div class="form-group col-md-8">
                            <div class="input-group">
                                <input type="number" class="form-control" id="cambio" wire:model='efectivo'>
                                <div class="input-group-append">
                                    <span class="input-group-text">Bs.</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="form-group col-md-3">
                    <label for="cambio">CAMBIO</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="cambio" wire:model='cambio' readonly>
                        <div class="input-group-append">
                            <span class="input-group-text">Bs.</span>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-3">
                    <label for="cancelado">TOTAL</label>
                    <div class="input-group">
                        <input type="number" class="form-control" wire:model='total' readonly>
                        <div class="input-group-append">
                            <span class="input-group-text">Bs.</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- BOTONES PARA REGISTRAR O CANCELAR UNA COMPRA -->

            <div class="form-row justify-content-center">

                <button class="btn btn-success ml-2" wire:click="guardarCompra">Registrar Compra</button>
                <button class="btn btn-danger ml-2" wire:click="cancelar">Cancelar</button>
            </div>

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
                            <label for="stockp">Stock</label>
                            <input type="number" class="form-control " id="stockp" wire:model.defer='stockp' readonly>

                        </div>
                        <div class="form-group col-md-3">
                            <label for="cantidadp">Cantidad</label>
                            <input type="number" class="form-control " id="cantidad" placeholder="0" wire:model.defer='cantidadp'>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="preciop">Precio Compra</label>
                            <input type="number" class="form-control " id="preciop" placeholder="0" wire:model.defer='precio_comprap'>
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
                                    <th scope="col">Precio_compra</th>
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
                                    <td>{{$prod->costo_producto." Bs."}}</td>
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
    <!-- Modal Para Mostrar Proveedores -->
    <div wire:ignore.self class="modal fade" id="modalProveedores" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Lista de Proveedores</h5>
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
                    @if (count($proveedores))
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="thead-dark text-center align-middle">
                                <tr>
                                    <th role="button" scope="col">Seleccione</th>
                                    <th role="button" scope="col">empresa</th>
                                    <th role="button" scope="col">celular</th>
                                    <th role="button" scope="col">direccion</th>
                                    <th role="button" scope="col">nit</th>
                                    <th role="button" scope="col">linea</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proveedores as $prov)
                                <tr>
                                    <th>
                                        <a class="btn btn-success" wire:click="agregarProveedor({{$prov->id}})"><i class="fa-solid fa-check"></i></a>
                                    </th>
                                    <td>{{$prov->empresa}}</td>
                                    <td>{{$prov->celular}}
                                    </td>
                                    <td>{{$prov->direccion}}</td>
                                    <td>{{$prov->nit}}</td>
                                    <td>{{$prov->categoria->nombre}}</td>

                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    @if ($proveedores->hasPages())

                    <div class="row justify-content-center">
                        {{$proveedores->links()}}
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
    <!-- PARA LAS ALEEEERTAAAAS -->
    @section('js')

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Livewire.on('alertaRapida-success-modal', (message) => {

            Swal.fire({
                position: 'top',
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 1500
            })
            $('#modalProveedores').modal('hide')
        });

        Livewire.on('alertaRapida-error', (message) => {

            Swal.fire({
                position: 'top',
                icon: 'error',
                title: message,
                showConfirmButton: false,
                timer: 1500

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
        Livewire.on('alertaRapida-success-fast', (message) => {

            Swal.fire({
                position: 'top',
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 700
            })
        });
    </script>

    @stop

</div>