<div>
    @section('title', 'CategoriasMP')
    <h1><b>Categorias | Materia Prima</b></h1>
    <div class="card">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-10">
                    <input type="text" class="form-control" placeholder="Ingresa el texto a buscar" wire:model="buscar">
                </div>
                <div class="form-group col-md-2">
                    @livewire('materiap.crear-categoria')
                </div>
            </div>
           
            @if (count($categorias))
            <table class="table table-striped table-hover table-bordered">
                <thead class="thead-dark text-center align-middle">
                    <tr>
                        <th role="button" scope="col" wire:click="ordenar('nombre')">
                            NOMBRE
                            @if ($ordenar_por == 'nombre')
                            @if ($direccion == 'asc')
                            <i class="fa-solid fa-arrow-down-a-z float-right mt-1"></i>
                            @else
                            <i class="fa-solid fa-arrow-down-z-a float-right mt-1"></i>
                            @endif
                            @else
                            <i class="fas fa-sort float-right mt-1"></i>

                            @endif
                        </th>
                        <th role="button" scope="col">DESCRIPCION</th>
                        <th role="button" scope="col">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                  
                    @foreach ($categorias as $cat)
                    <tr>
                        
                        <td>{{$cat->nombre}}</td>
                        <td>{{$cat->descripcion}}</td>
                        <td>
                            <div class="row d-block">
                                <!-- Button trigger modal Para Editar-->
                                <a wire:click="editar({{$cat->id}})" class="btn btn-success" data-toggle="modal" data-target="#modalEditar"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a wire:click="$emit('eliminarCat',{{$cat->id}})" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                            </div>

                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
            @if ($categorias->hasPages())

            <div class="row justify-content-center">
                {{$categorias->links()}}
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
                            <h5 class="modal-title" id="exampleModalLabel">Editar Categoria</h5>
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
                                <input type="text" class="disabled form-control" id="descripcion" wire:model.defer="descripcion">

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
        Livewire.on('eliminarCat', catId => {
            Swal.fire({
                title: 'Esta seguro de eliminar la Categoria?',
                text: "La eliminacion no se podra revertir!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Eliminar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emitTo('materiap.categoria-prima', 'eliminar', catId)
                    Swal.fire(
                        'Eliminado!',
                        'La categoria ha sido eliminada',
                        'success'
                    )
                }
            })

        })
    </script>
    @stop
</div>