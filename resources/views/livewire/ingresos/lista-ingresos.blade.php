<div>
    @section('title', 'ListaIngresos')
    <h1><b>Lista de Ingresos</b></h1>
    <div class="card">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-10">
                    <input type="text" class="form-control" placeholder="Ingresa el texto a buscar" wire:model="buscar">
                </div>
                <div class="form-group col-md-2">
                    <a class="btn btn-danger form-control" href="{{route('registro_ingresos')}}">Registrar Ingreso</a>
                </div>
            </div>
            @if (count($ingresos))
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="thead-dark text-center align-middle">
                        <tr>
                            <th scope="col" role="button" class="align-middle" wire:click="ordenar('id')">
                                NRO
                                @if ($ordenar_por == 'id')
                                @if ($direccion == 'asc')
                                <i class="fa-solid fa-arrow-down-1-9 float-right mt-1"></i>
                                @else
                                <i class="fa-solid fa-arrow-down-9-1  float-right mt-1"></i>
                                @endif
                                @else
                                <i class="fas fa-sort float-right mt-1"></i>
                                @endif
                            </th>
                            <th scope="col" role="button" class="align-middle" wire:click="ordenar('fecha')">
                                FECHA
                                @if ($ordenar_por == 'fecha')
                                @if ($direccion == 'asc')
                                <i class="fa-solid fa-arrow-down-1-9 float-right mt-1"></i>
                                @else
                                <i class="fa-solid fa-arrow-down-9-1 float-right mt-1"></i>
                                @endif
                                @else
                                <i class="fas fa-sort float-right mt-1"></i>
                                @endif
                            </th>
                            <th scope="col" role="button" class="align-middle">USUARIO</th>
                            <th scope="col" role="button" class="align-middle">PRODUCTOS</th>
                            <th scope="col" role="button" class="align-middle">ACCIONES</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ingresos as $item)
                        <tr>
                            <th scope="row" class="text-center">{{$item->id}}</th>
                            <td>{{$item->fecha}}</td>
                            <td>{{$item->user->name}}</td>
                            <td>
                                <dl>
                                    @foreach ($item->productos as $prod)
                                    <li><strong class="mr-1">Nombre:</strong>{{$prod->nombre}}
                                        <strong class="mr-1">Cantidad:</strong>{{$prod->pivot->cantidad}}
                                    </li>
                                    @endforeach
                                </dl>

                            </td>
                            <td>
                                <div class="row d-block">
                                    <a class="btn btn-danger" wire:click="$emit('eliminarIngreso',{{$item->id}})"><i class="fa-solid fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            @if ($ingresos->hasPages())

            <div class="row justify-content-center">
                {{$ingresos->links()}}
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
        Livewire.on('eliminarIngreso', catId => {
            Swal.fire({
                title: 'Esta seguro de eliminar el Ingreso?',
                text: "La eliminacion no se podra revertir!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Eliminar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emitTo('ingresos.lista-ingresos', 'eliminar', catId)
                    Swal.fire(
                        'Eliminado!',
                        'El ingreso ha sido eliminado',
                        'success'
                    )
                }
            })

        })
        Livewire.on('alertaRapida-success', (message) => {

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