<div>
    @section('title', 'Proveedores')
    <h1><b>Proveedores</b></h1>
    <div class="card">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-10">
                    <input type="email" class="form-control" placeholder="Ingresa el texto a buscar" wire:model="buscar">
                </div>
                <div class="form-group col-md-2">
                    @livewire('proveedores.crear-proveedor')
                </div>
            </div>
            @if (count($proveedores))
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="thead-dark text-center align-middle">
                        <tr>
                            <th role="button" scope="col" wire:click="ordenar('id')">NRO
                                @if ($ordenar_por == 'id')
                                @if ($direccion == 'asc')
                                <i class="fa-solid fa-arrow-down-1-9 float-right mt-1"></i>
                                @else
                                <i class="fa-solid fa-arrow-down-9-1 float-right mt-1"></i>
                                @endif
                                @else
                                <i class="fas fa-sort float-right mt-1"></i>

                                @endif
                            </th>
                            <th role="button" scope="col" wire:click="ordenar('empresa')">EMPRESA
                                @if ($ordenar_por == 'empresa')
                                @if ($direccion == 'asc')
                                <i class="fa-solid fa-arrow-down-a-z float-right mt-1"></i>
                                @else
                                <i class="fa-solid fa-arrow-down-z-a float-right mt-1"></i>
                                @endif
                                @else
                                <i class="fas fa-sort float-right mt-1"></i>

                                @endif
                            </th>
                            <th role="button" scope="col">CELULAR</th>
                            <th role="button" scope="col">DIRECCION</th>
                            <th role="button" scope="col">NIT</th>
                            <th role="button" scope="col">CATEGORIA</th>
                            <th role="button" scope="col">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($proveedores as $prov)
                        <tr>
                            <th class="text-center">{{$prov->id}}</th>
                            <td>{{$prov->empresa}}</td>
                            <td>{{$prov->celular}}</td>
                            <td>{{$prov->direccion}}</td>
                            <td>{{$prov->nit}}</td>
                            <td>{{$prov->categoria->nombre}}</td>
                            <td>
                                <div class="row justify-content-center">
                                    <a wire:click="mostrar({{$prov->id}})" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal2"><i class="fa-solid fa-rectangle-list"></i></a>
                                    <!-- Button trigger modal Para Editar-->
                                    <a wire:click="mostrar({{$prov->id}})" class="btn btn-success" data-toggle="modal" data-target="#exampleModal3"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a wire:click="$emit('eliminarProv',{{$prov->id}})" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                                </div>

                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            <!-- Modal Para Mostrar-->
            <div wire:ignore.self class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Datos Proveedor</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div wire:loading wire:target="mostrar" class="text-center text-primary">
                            <div class="spinner-border" role="status">
                            </div>
                        </div>

                        <fieldset disabled>
                            <div class="modal-body" wire:loading.remove wire:target="mostrar">
                                <div class="form-group">
                                    <label for="empresam">Empresa</label>
                                    <input type="text" class="disabled form-control" id="empresam" wire:model="empresap">
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="celularm">Celular</label>
                                        <input type="text" class="form-control" id="celularm" wire:model="celularp">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="nitm">Nit</label>
                                        <input type="text" class="form-control" id="nitm" wire:model="nitp">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="direccionm">Direccion</label>
                                    <input type="text" class="disabled form-control" id="direccionm" wire:model="direccionp">
                                </div>

                                <div class="form-group">
                                    <label for="categoriam">Categoria</label>
                                    <input type="text" class="disabled form-control" id="categoriam" wire:model="categoriap">
                                </div>
                            </div>
                        </fieldset>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Editar-->
            <div wire:ignore.self class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Editar Proveedor</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div wire:loading wire:target="mostrar" class="text-center text-primary">
                            <div class="spinner-border" role="status">
                            </div>
                        </div>

                        <div class="modal-body" wire:loading.remove wire:target="mostrar">
                            <div class="form-group">
                                <label for="Nombre">Empresa</label>
                                <input type="text" class="form-control" id="Nombre" wire:model.defer="empresap">
                                @error('empresap')
                                <small class="form-text text-danger">{{$message}}</small>
                                @enderror

                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="celular">Celular</label>
                                    <input type="text" class="form-control" id="celular" wire:model.defer="celularp">

                                </div>
                                <div class="form-group col-md-6">
                                    <label for="nit">Nit</label>
                                    <input type="text" class="form-control" id="nit" wire:model.defer="nitp">

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="direccion">Direccion</label>
                                <input type="text" class="disabled form-control" id="direccion" wire:model.defer="direccionp">

                            </div>

                            <div class="form-group">
                                <label for="categorias">Categoria</label>

                                <select class="custom-select" wire:model.defer="categoriaidp">
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
        </div>



        @section('js')

        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Livewire.on('ocultarEdit', (message) => {
                Swal.fire(
                    'Buen trabajo!',
                    message,
                    'success'
                )
                $('#exampleModal3').modal('hide')
            });
        </script>
        <script>
            Livewire.on('ocultarGuardar', (message) => {
                Swal.fire(
                    'Buen trabajo!',
                    message,
                    'success'
                )
                $('#exampleModal').modal('hide')
            });
        </script>
        <script>
            Livewire.on('eliminarProv', catId => {
                Swal.fire({
                    title: 'Esta seguro de eliminar al proveedor?',
                    text: "La eliminacion no se podra revertir!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Eliminar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('proveedores.proveedores', 'eliminar', catId)
                        Swal.fire(
                            'Eliminado!',
                            'El proveedor ha sido eliminado',
                            'success'
                        )
                    }
                })

            })
        </script>
        @stop
    </div>