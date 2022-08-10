<div>
    @section('title', 'Roles')
    <h1><b>Roles</b></h1>
    <div class="card">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-10">
                    <input type="text" class="form-control" placeholder="Ingresa el texto a buscar" wire:model="buscar">
                </div>
                <div class="form-group col-md-2">
                    <button class="form-control btn btn-danger"  data-toggle="modal" data-target="#modalCrear">Crear Rol</button>
                </div>
            </div>
            @if (count($roles))
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="thead-dark text-center align-middle">
                        <tr>
                            <th role="button" scope="col">ID</th>
                            <th role="button" scope="col">NOMBRE</th>
                            <th role="button" scope="col">ACCIONES</th>

                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        @foreach ($roles as $item)
                        <tr>
                            <th scope="row">{{$item->id}}</th>
                            <td>{{$item->name}}</td>
                            <td>
                                <div class="row d-block">
                                    <!-- Button trigger modal Para Editar-->
                                    <a wire:click="editar({{$item->id}})" class="btn btn-success" data-toggle="modal" data-target="#modalEditar"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a wire:click="$emit('eliminarRol',{{$item->id}})" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                                </div>

                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <!-- Aqui viene la parte de paginacion -->
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
                            <h5 class="modal-title" id="exampleModalLabel">Editar Usuario</h5>
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
                                <input type="text" class="form-control" id="nombre" wire:model.defer="nombrer">
                                @error('nombrer')
                                <small class="form-text text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" wire:click="modificar">Actualizar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Crear-->
            <div wire:ignore.self class="modal fade" id="modalCrear" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Crear Rol</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="resetear">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div wire:loading wire:target="resetear" class="text-center text-primary">
                            <div class="spinner-border" role="status">
                            </div>
                        </div>

                        <div class="modal-body" wire:loading.remove wire:target="resetear">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" wire:model.defer="nombre">
                                @error('nombre')
                                <small class="form-text text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="resetear">Cerrar</button>
                            <button type="button" class="btn btn-primary" wire:click="guardar" >Crear Rol</button>
                        </div>
                    </div>
                </div>
            </div>






        </div>

    </div>

    @section('js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Livewire.on('alertaRapida-success-editar', (message) => {

            Swal.fire({
                position: 'top',
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 1500
            })
            $('#modalEditar').modal('hide')
        });
        Livewire.on('alertaRapida-success-crear', (message) => {

            Swal.fire({
                position: 'top',
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 1500
            })
            $('#modalCrear').modal('hide')
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
        Livewire.on('eliminarRol', catId => {
            Swal.fire({
                title: 'Esta seguro de eliminar el Rol?',
                text: "La eliminacion no se podra revertir!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Eliminar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emitTo('roles.lista-roles', 'eliminar', catId)
                    Swal.fire(
                        'Eliminado!',
                        'El Rol ha sido eliminado',
                        'success'
                    )
                }
            })

        });
    </script>
    @stop
</div>