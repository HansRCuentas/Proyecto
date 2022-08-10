<div>
    @section('title', 'Usuarios')
    <h1><b>Usuarios</b></h1>
    <div class="card">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-10">
                    <input type="text" class="form-control" placeholder="Ingresa el texto a buscar" wire:model="buscar">
                </div>
                <div class="form-group col-md-2">
                    @livewire('usuarios.registrar-usuario')
                </div>
            </div>
            @if (count($users))
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="thead-dark text-center align-middle">
                        <tr>
                            <th role="button" scope="col">IMAGEN</th>
                            <th role="button" scope="col">NOMBRE</th>
                            <th role="button" scope="col">CORREO</th>
                            <th role="button" scope="col">ROL</th>
                         
                            <th role="button" scope="col">ACCIONES</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $item)
                        <tr>
                            <td width="180">
                                @if($item->profile_photo_path)
                                <img src="{{asset('storage/'.$item->profile_photo_path)}}" class="text-center" width="100%" height="100%">
                                @endif
                            </td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->email}}</td>
                            <td>

                                @if (count($item->roles)>0)
                                {{$item->roles[0]->name}}
                                @endif
        
                            </td>

                            <td>
                                <div class="row d-block">
                                    <!-- Button trigger modal Para Editar-->
                                    <a wire:click="editar({{$item->id}})" class="btn btn-success" data-toggle="modal" data-target="#modalEditar"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a wire:click="$emit('eliminarUsuario',{{$item->id}})" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                                </div>

                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            @if ($users->hasPages())

            <div class="row justify-content-center">
                {{$users->links()}}
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
                                <input type="text" class="form-control" id="nombre" wire:model.defer="nombre">
                                @error('nombre')
                                <small class="form-text text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" wire:model.defer="email">
                                @error('email')
                                <small class="form-text text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">Contraseña</label>
                                <input type="text" class="form-control" id="password" wire:model.defer="password">
                                @error('password')
                                <small class="form-text text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="Imagen">Imagen</label>
                                <input type="file" class="form-control-file" id="{{$identificador}}" wire:model.defer="imagen2">
                            </div>
                            <div wire:loading wire:target="imagen2" class="alert alert-danger" role="alert">
                                <strong>Cargando Imagen!</strong> Espere un momento hasta que la imagen se haya procesado
                            </div>
                            @if ($imagen2)
                            <img src="{{ $imagen2->temporaryUrl() }}" class="img-thumbnail">
                            @elseif($imagen)
                            <img src="{{asset('storage/'.$imagen)}}" class="img-thumbnail">
                            @endif

                            <div class="form-group">
                                <label for="categorias">Roles</label>
                                <select class="custom-select" wire:model.defer="role">
                                    <option value="" selected>--Seleccione Un Rol--</option>
                                    @foreach($roles as $rol)
                                    <option value="{{$rol->id}}">{{$rol->name}}</option>
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
            Livewire.on('alertaRapida-success-modal', (message) => {

                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: message,
                    showConfirmButton: false,
                    timer: 1500
                })
                $('#modalEditar').modal('hide')
            });
            Livewire.on('alertaRapida-success-modalCrear', (message) => {

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
            Livewire.on('eliminarUsuario', catId => {
                Swal.fire({
                    title: 'Esta seguro de eliminar al usuario?',
                    text: "La eliminacion no se podra revertir!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Eliminar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('usuarios.lista-usuarios', 'eliminar', catId)
                        Swal.fire(
                            'Eliminado!',
                            'El usuario ha sido eliminado',
                            'success'
                        )
                    }
                })

            });
        </script>
        @stop
    </div>