<div>
    @section('title', 'Asignar Permisos')
    <h1><b>Asignar Permisos</b></h1>
    <div class="card">
        <div class="card-body">
            <div class="form-row">
                <select class="form-control col-md-2" wire:model="role">
                    <option value="Elegir" selected>--Seleccione un rol--</option>
                    @foreach ($roles as $rol)
                    <option value="{{$rol->id}}">{{$rol->name}}</option>
                    @endforeach
                </select>
                <div class="form-group col-md-2">
                    <button class="form-control btn btn-success" wire:click.prevent="SyncAll">Asignar todos</button>
                </div>
                <div class="form-group col-md-2">
                    <button class="form-control btn btn-danger" wire:click="$emit('Revocar')">Revocar todos</button>
                </div>

            </div>
            @if (count($permisos))
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="thead-dark text-center align-middle">
                        <tr>
                            <th role="button" scope="col">ID</th>
                            <th role="button" scope="col">PERMISO</th>
                            <th role="button" scope="col">PERMISO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permisos as $permiso)
                        <tr>
                            <th scope="row">{{$permiso->id}}</th>
                            <td>{{$permiso->name}}</td>
                            <td>
                            <div class="form-check">
                                <input class="form-check-input"
                                 type="checkbox" 
                                 wire:change="SyncPermiso($('#p' + {{$permiso->id}})
                                        .is(':checked'),'{{$permiso->name}}')"
                                 id="p{{$permiso->id}}"
                                 value="{{$permiso->id}}"
                                 {{$permiso->checked == 1 ? 'checked' : ''}}
                                 >
                                <label class="form-check-label" for="p{{$permiso->id}}">
                                    {{$permiso->descripcion}}
                                </label>
                            </div>
                            </td>
                            
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <!-- Aqui viene la parte de paginacion -->
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
        Livewire.on('Revocar', catId => {
            Swal.fire({
                title: 'Esta seguro de revocar los Permisos?',
                text: "La accion no se podra revertir!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Eliminar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emitTo('asignar.asignar-permisos', 'revokeall', catId)
                    Swal.fire(
                        'Eliminado!',
                        'Los permisos han sido revokados',
                        'success'
                    )
                }
            })

        });
    </script>
    @stop
</div>