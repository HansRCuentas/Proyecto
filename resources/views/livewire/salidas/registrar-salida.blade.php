<div>
    @section('title', 'Salida Materia Prima')
    <h1><b>Registro Salida de Productos Materia Prima</b></h1>
    <div class="card">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group form-inline col-md-4">
                    <label for="nro">FECHA</label>
                    <input type="text" class="form-control ml-3 text-center" id="nro" readonly value="{{date('Y-m-d')}}">
                </div>

            </div>
            <div class="form-row">
                <button class="form-group btn btn-info" data-toggle="modal" data-target="#modalProductos"><i class="fa-solid fa-magnifying-glass"></i>Buscar Productos</button>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="thead-dark text-center align-middle">
                        <tr>
                            <th scope="col">NRO_CODIGO</th>
                            <th scope="col">NOMBRE</th>
                            <th scope="col">CATEGORIA</th>
                            <th scope="col">STOCK ACTUAL</th>
                            <th scope="col">CANTIDAD A SALIR</th>
                            <th scope="col">ACCIONES</th>
                        </tr>
                    </thead>
                    @if (count($cart))
                    <tbody>
                        @foreach ($cart as $item)
                        <tr>
                            <td>{{$item->attributes->codigo}} </td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->attributes->categoria}}</td>
                            <td>{{$item->attributes->stock}} </td>
                            <td>
                            <input class="form-control" type="number" id="r{{$item->id}}" wire:change.prevent="actualizarCant({{$item->id}}, $('#r' + {{$item->id}}).val() )" value="{{$item->quantity}}">
                        
                            </td>
                            <td>
                                <div class="row d-block">
                                    <!-- Boton para eliminar del carrito-->
                                    <a class="btn btn-danger" wire:click="eliminarProdCart({{$item->id}})"><i class="fa-solid fa-trash"></i> </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td> <strong>CANTIDAD TOTAL:</strong></td>
                            <td>
                                <input class="form-control bg-warning" type="number" value="{{$cantidadTotal}}" readonly>

                            </td>
                        </tr>
                    </tbody>
                    @endif

                </table>
            </div>
            <div class="form-row justify-content-center">

                <button class="btn btn-success ml-2" wire:click="guardarSalida">Registrar Salida</button>
                <button class="btn btn-danger ml-2" wire:click="cancelar">Cancelar</button>
            </div>
        </div>
        <!-- Modal Para Mostrar productos -->
        <div wire:ignore.self class="modal fade" id="modalProductos" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Lista de Productos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body container">
                        <!--  PRODUCTO SELECCIONADO -->
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="nombrep">Nombre</label>
                                <input type="text" class="form-control " id="nombre" wire:model.defer='nombrep' readonly>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="cantidad">Cantidad a Salir</label>
                                <input type="number" class="form-control " id="cantidad" placeholder="0" wire:model.defer='cantidadp'>

                            </div>

                            <div class="form-group col-md-3">
                                <label for="stockp">Stock Actual</label>
                                <input type="number" class="form-control " id="stockp" wire:model.defer='stockp' readonly>

                            </div>

                            <div class="form-group col-md-3">
                                <label for="preciop">Categoria</label>
                                <input type="text" class="form-control " id="preciop" wire:model.defer='categoriap' readonly>
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
                                        <td>{{$prod->precio_venta}}</td>
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click='limpiarProducto'>Cerrar</button>

                    </div>

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
                timer: 1000
            })
        });
        Livewire.on('alertaMedia-success', (message) => {

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