<div>
    @section('title', 'Proveedores')
    <h1><b>Productos de Materia Prima</b></h1>
    <div class="card">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-10">
                    <input type="email" class="form-control" placeholder="Ingresa el texto a buscar" wire:model="buscar">
                </div>
                <div class="form-group col-md-2">
                    @livewire('materiap.crear-primas')
                </div>
            </div>
            @if (count($productos))
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="thead-dark text-center align-middle">
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
                            <th role="button" scope="col" class="align-middle">STOCK_MINIMO</th>
                            <th role="button" scope="col" class="align-middle">COSTO_PRODUCTO</th>
                            <th role="button" scope="col" class="align-middle">DISPONIBILIDAD</th>
                            <th role="button" scope="col" class="align-middle">CATEGORIA</th>
                            <th role="button" scope="col" class="align-middle">ACCIONES</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productos as $prod)
                        <tr>
                            <th scope="row" class="text-center">{{$prod->id}}</th>
                            <td>{{$prod->nombre}}</td>
                            <td>{{$prod->descripcion}}</td>
                            <td>{{$prod->nro_codigo}}</td>
                            <td>{{$prod->stock}}</td>
                            <td>{{$prod->stock_minimo}}</td>
                            <td>{{$prod->costo_producto}}</td>
                            <td>

                                @if ($prod->stock > $prod->stock_minimo)
                                <h5><span class="badge badge-success">En Stock</span></h5>
                                @else
                                @if($prod->stock == 0)
                                <h5><span class="badge badge-danger">Agotado</span></h5>
                                @else
                                <h5><span class="badge badge-warning">Bajo Stock</span></h5>
                                @endif
                                @endif
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
            <div class="form-row">
                <div class="form-group col-md-12">
                    <h3>No se encontraron registros</h3>
                </div>
            </div>
            @endif

            <!-- Modal Editar-->
            <div wire:ignore.self class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Editar Producto</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div wire:loading wire:target="editar" class="text-center text-primary">
                            <div class="spinner-border" role="status">
                            </div>
                        </div>

                        <div class="modal-body" wire:loading.remove wire:target="editar">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" wire:model.defer="nombre">
                                @error('nombre')
                                <small class="form-text text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="descripcion">Descripcion</label>
                                <input type="text" class="form-control" id="descripcion" wire:model.defer="descripcion">
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="codigo">Nro de Codigo</label>
                                    <input type="text" class="form-control" id="codigo" wire:model.defer="nro_codigo">

                                </div>
                                <div class="form-group col-md-6">
                                    <label for="costo">Costo</label>
                                    <input type="number" class="form-control" id="costo" wire:model.defer="costo_producto">

                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="stock">Stock</label>
                                    <input type="number" class="form-control" id="stock" wire:model.defer="stock">

                                </div>
                                <div class="form-group col-md-6">
                                    <label for="stockmin">Stock Minimo</label>
                                    <input type="number" class="form-control" id="stockmin" wire:model.defer="stock_minimo">

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="categorias">Categoria</label>
                                <select class="custom-select" wire:model.defer="categoria_id">
                                    @foreach($categorias as $cat)
                                    <option value="{{$cat->id}}">{{$cat->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-primary" wire:click="modificar">Actualizar</button>
                            </div>
                        </div>
                    </div>
                </div>





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
                        Livewire.emitTo('materiap.primas', 'eliminar', catId)
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