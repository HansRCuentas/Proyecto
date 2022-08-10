<div>
    @section('title', 'Productos')
    <h1><b>Productos Terminados</b></h1>
    <div class="card">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-10">
                    <input type="text" class="form-control" placeholder="Ingresa el texto a buscar" wire:model="buscar">
                </div>
                <div class="form-group col-md-2">
                    @livewire('productos.crear-producto')
                </div>
            </div>
            <div>
                @if (count($productos))
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered">
                        <thead class="thead-dark text-center">
                            <tr>
                                <th role="button" scope="col" class="align-middle" wire:click="ordenar('id')">
                                    <span class="d-block text-white">NRO</span>
                                    <span class="d-block text-white text-center">
                                        @if ($ordenar_por == 'id')
                                        @if ($direccion == 'asc')
                                        <i class="fa-solid fa-arrow-down-1-9 "></i>
                                        @else
                                        <i class="fa-solid fa-arrow-down-9-1  "></i>
                                        @endif
                                        @else
                                        <i class="fas fa-sort "></i>
                                        @endif
                                    </span>
                                </th>
                                <th role="button" scope="col" class="align-middle">
                                    IMAGEN
                                </th>
                                <th role="button" scope="col" class="align-middle" wire:click="ordenar('nombre')">
                                    <span class="d-block text-white">NOMBRE</span>
                                    <span class="d-block text-white text-center">
                                        @if ($ordenar_por == 'nombre')
                                        @if ($direccion == 'asc')
                                        <i class="fa-solid fa-arrow-down-a-z "></i>
                                        @else
                                        <i class="fa-solid fa-arrow-down-z-a "></i>
                                        @endif
                                        @else
                                        <i class="fas fa-sort "></i>

                                        @endif
                                    </span>
                                </th>

                                <th role="button" scope="col" class="align-middle">DESCRIPCION</th>
                                <th role="button" scope="col" class="align-middle">CODIGO</th>
                                <th role="button" scope="col" class="align-middle">STOCK</th>
                                <th role="button" scope="col" class="align-middle">STOCK MINIMO</th>
                                <th role="button" scope="col" class="align-middle">DISP.</th>
                                <th role="button" scope="col" class="align-middle">COSTO PRODUCTO</th>
                                <th role="button" scope="col" class="align-middle" width="170">PRECIO VENTA</th>

                                <th role="button" scope="col" class="align-middle">CATEGORIA</th>
                                <th role="button" scope="col" class="align-middle">ACCIONES</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productos as $prod)
                            <tr>
                                <th class="text-center">
                                    {{$prod->id}}
                                </th>
                                <td>
                                    @if($prod->imagen)
                                    <img src="{{asset('storage/'.$prod->imagen)}}" width="120" height="120">
                                    @endif
                                </td>
                                <td>{{$prod->nombre}}</td>
                                <td>{{$prod->descripcion}}</td>
                                <td>{{$prod->nro_codigo}}</td>
                                <td>{{$prod->stock}}</td>
                                <td>{{$prod->stock_minimo}}</td>
                                <td>

                                    @if ($prod->stock > $prod->stock_minimo)
                                    <h5><span class="badge badge-success">En Stock</span></h5>
                                    @else
                                    @if($prod->stock == 0)
                                    <h5><span class="badge badge-danger">Agotado</span></h5>
                                    @else
                                    <h5><span class="badge badge-info">Bajo Stock</span></h5>
                                    @endif
                                    @endif
                                </td>
                                <td>{{$prod->costo_producto}}</td>
                                <td width="300px">
                                    {{$prod->precio_venta." Bs."}} <br>
                                    @foreach ($prod->precios as $precio)
                                    <div class="row">
                                        <strong>*</strong> {{$precio->cantidad}}u.-{{$precio->precio}}Bs.<br>

                                    </div>
                                    @endforeach
                                </td>


                                <td>{{$prod->categoria->nombre}}</td>
                                <td>
                                    <div class="row d-block">
                                        <!-- Button trigger modal Para Editar-->
                                        <a wire:click="editar({{$prod->id}})" class="btn btn-success" data-toggle="modal" data-target="#modalEditar"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <a wire:click="$emit('eliminarProd',{{$prod->id}})" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                                    </div>

                                </td>
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
                <div class="alert alert-warning" role="alert">
                    <strong>No se encontraron Registros</strong>
                </div>
                @endif
            </div>



        </div>



        @section('js')

        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Livewire.on('ocultarEditar', (message) => {
                Swal.fire(
                    'Buen trabajo!',
                    message,
                    'success'
                )
                $('#modalEditar').modal('hide')
            });
        </script>
        <script>
            Livewire.on('ocultarCrear', (message) => {
                Swal.fire(
                    'Buen trabajo!',
                    message,
                    'success'
                )
                $('#modalCrear').modal('hide')
            });
        </script>
        <script>
            Livewire.on('eliminarProd', catId => {
                Swal.fire({
                    title: 'Esta seguro de eliminar el producto?',
                    text: "La eliminacion no se podra revertir!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Eliminar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('productos.productos', 'eliminar', catId)
                        Swal.fire(
                            'Eliminado!',
                            'El producto ha sido eliminado',
                            'success'
                        )
                    }
                })

            })
        </script>
        @stop
    </div>