<div>
    @section('title', 'Proveedores')
    <h1><b>Clientes</b></h1>
    <div class="card">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-10">
                    <input type="text" class="form-control" placeholder="Ingresa el texto a buscar" wire:model="buscar">
                </div>
                <div class="form-group col-md-2">
                    @livewire('clientes.crear-cliente')
                </div>
            </div>
            @if (count($clientes))
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="thead-dark text-center align-middle">
                        <tr>
                            <th role="button" scope="col" wire:click="ordenar('id')">
                                NRO.
                                @if ($ordenar_por == 'id')
                                @if ($direccion == 'asc')
                                <i class="fa-solid fa-arrow-down-1-9 float-right mt-1 "></i>
                                @else
                                <i class="fa-solid fa-arrow-down-9-1 float-right mt-1"></i>
                                @endif
                                @else
                                <i class="fas fa-sort float-right mt-1"></i>

                                @endif
                            </th>
                            <th role="button" scope="col" wire:click="ordenar('nombre')">
                                NOMBRE
                                @if ($ordenar_por == 'nombre')
                                @if ($direccion == 'asc')
                                <i class="fa-solid fa-arrow-down-a-z float-right mt-1 "></i>
                                @else
                                <i class="fa-solid fa-arrow-down-z-a float-right mt-1"></i>
                                @endif
                                @else
                                <i class="fas fa-sort float-right mt-1"></i>

                                @endif
                            </th>
                            <th role="button" scope="col">CELULAR</th>
                            <th role="button" scope="col">DIRECCION</th>
                            <th role="button" scope="col">REGION</th>
                            <th role="button" scope="col">CEDULA</th>
                            <th role="button" scope="col">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientes as $clie)
                        <tr>
                            <th scope="row" class="text-center">{{$clie->id}}</th>
                            <td>{{$clie->nombre}}</td>
                            <td>{{$clie->celular}}</td>
                            <td>{{$clie->direccion}}</td>
                            <td>{{$clie->region}}</td>
                            <td>{{$clie->cedula}}</td>
                            <td>
                                <div class="row justify-content-center">
                                    <!-- Button trigger modal Para Editar-->
                                    <a wire:click="editar({{$clie->id}})" class="btn btn-success" data-toggle="modal" data-target="#modalEditar"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a wire:click="$emit('eliminarClie',{{$clie->id}})" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                                </div>

                            </td>
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

            <!-- Modal Editar-->
            <div wire:ignore.self class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Editar Cliente</h5>
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
                                <label for="Nombre">Nombre</label>
                                <input type="text" class="form-control" id="Nombre" wire:model.defer="nombre">
                                @error('nombre')
                                <small class="form-text text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="Nombre">Direccion</label>
                                <input type="text" class="form-control" id="Nombre" wire:model.defer="direccionc">
                                @error('direccionc')
                                <small class="form-text text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="region">Region</label>
                                <select class="custom-select" size="4" wire:model="region">
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


                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="celular">Celular</label>
                                    <input type="text" class="form-control" id="celular" wire:model.defer="celular">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="cedula">Cedula</label>
                                    <input type="text" class="form-control" id="cedula" wire:model.defer="cedula">
                                </div>
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
            Livewire.on('eliminarClie', catId => {
                Swal.fire({
                    title: 'Esta seguro de eliminar al cliente?',
                    text: "La eliminacion no se podra revertir!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Eliminar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('clientes.clientes', 'eliminar', catId)
                        Swal.fire(
                            'Eliminado!',
                            'El cliente ha sido eliminado',
                            'success'
                        )
                    }
                })

            })
        </script>
        @stop
    </div>