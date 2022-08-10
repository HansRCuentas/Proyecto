<div>
    @section('title', 'ListaVentas')
    <h1><b>Lista de Ventas</b></h1>
    <div class="card">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-10">
                    <input type="text" class="form-control" placeholder="Ingresa el texto a buscar" wire:model="buscar">
                </div>
                <div class="form-group col-md-2">
                    <a class="btn btn-danger form-control" href="{{route('registro_ventas')}}">Registrar Venta</a>
                </div>
            </div>
            @if (count($ventas))
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="thead-dark text-center align-middle">
                        <tr>
                            <th scope="col" role="button" class="align-middle" wire:click="ordenar('nro_venta')">
                                <span class="d-block text-white">NRO</span>
                                <span class="d-block text-white text-center">
                                    @if ($ordenar_por == 'nro_venta')
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
                            <th scope="col" role="button" class="align-middle">TIPO PAGO</th>
                            <th scope="col" role="button" class="align-middle" wire:click="ordenar('fecha')">

                                <span class="d-block text-white">FECHA</span>
                                <span class="d-block text-white text-center">
                                    @if ($ordenar_por == 'fecha')
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

                            <th scope="col" role="button" class="align-middle">AUMENTO</th>
                            <th scope="col" role="button" class="align-middle">DESCUENTO</th>
                            <th scope="col" role="button" class="align-middle">EFECTIVO</th>
                            <th scope="col" role="button" class="align-middle">TOTAL</th>
                            <th scope="col" role="button" class="align-middle">GANANCIA</th>
                            <th scope="col" role="button" class="align-middle">REGION</th>
                            <th scope="col" role="button" class="align-middle">CLIENTE</th>
                            <th scope="col" role="button" class="align-middle">USUARIO</th>
                            <th scope="col" role="button" class="align-middle" width="100">ACCIONES</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ventas as $venta)
                        <tr>
                            <th scope="row" class="text-center">{{$venta->nro_venta}}</th>
                            <td>{{$venta->tipo_pago}}</td>
                            <td>{{$venta->fecha}}</td>
                          
                            <td>{{$venta->aumento." Bs."}}</td>
                            <td>{{$venta->descuento." Bs."}}</td>
                            <td>{{$venta->efectivo." Bs."}}</td>
                            <td>
                                <h5><span class="badge badge-success">{{$venta->total." Bs."}}</span></h5>
                            </td>
                            <td>
                                <h5><span class="badge badge-info">{{$venta->ganancia." Bs."}}</span></h5>
                            </td>
                            <td>{{$venta->region}}</td>
                            <td>{{$venta->cliente->nombre}}</td>
                            <td>{{$venta->user->name}}</td>
                            <td>
                                <div class="row justify-content-center">
                                    <a class="btn btn-success" wire:click="descargarPdf({{$venta->id}})"><i class="fa-solid fa-file-pdf"></i></a>
                                    <a class="btn btn-primary" data-toggle="modal" data-target="#modalMostrar" wire:click="mostrar({{$venta->id}})"><i class="fa-solid fa-rectangle-list"></i></a>
                                    <a class="btn btn-danger" wire:click="$emit('eliminarVenta',{{$venta->id}})"><i class="fa-solid fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            @if ($ventas->hasPages())
            <div class="row justify-content-center">
                {{$ventas->links()}}
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
        <!-- Modal para mostrar el detalle de venta -->
        <div wire:ignore.self class="modal fade" id="modalMostrar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detalle de Venta</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div wire:loading wire:target="mostrar" class="text-center text-primary">
                        <div class="spinner-border" role="status">
                        </div>
                    </div>
                    <div class="modal-body container" wire:loading.remove wire:target="mostrar">
                        <fieldset disabled>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="nro">Nro Venta</label>
                                    <input type="text" class="form-control " id="nro" value="{{$venta2->nro_venta}}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="fecha">Fecha</label>
                                    <input type="text" class="form-control " id="fecha" value="{{$venta2->fecha}}">

                                </div>

                                <div class="form-group col-md-4">
                                    <label for="region">Region</label>
                                    <input type="text" class="form-control " id="region" value="{{$venta2->region}}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-7">
                                    <label for="cliente">Cliente</label>

                                    <input type="text" class="form-control " id="cliente" value="{{$cliente->nombre. ' ' . $cliente->cedula}}">

                                </div>
                                <div class="form-group col-md-5">
                                    <label for="usuario">Usuario</label>
                                    <input type="text" class="form-control " id="usuario" value="{{$user->name}}">

                                </div>
                            </div>
                            @if (count($venta2->productos))
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="thead-dark text-center align-middle">
                                        <tr>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Precio</th>
                                            <th scope="col">Cantidad</th>
                                            <th scope="col">Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($venta2->productos as $prod)
                                        <tr>

                                            <td>{{$prod->nombre}}</td>
                                            <td>{{$prod->precio_venta}}
                                            </td>
                                            <td>{{$prod->pivot->cantidad}}</td>
                                            <td>{{$prod->precio_venta*$prod->pivot->cantidad." Bs."}}</td>

                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td><strong>SUB TOTAL:</strong></td>
                                            <td><strong>{{$total_medio." Bs."}}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @endif
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="aumento">Aumento</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="aumento" value="{{$venta2->aumento}}" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Bs.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="descuento">Descuento</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="descuento" value="{{$venta2->descuento}}" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Bs.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="monto">Monto Cancelado</label>

                                    <div class="input-group">
                                        <input type="text" class="form-control" id="monto" value="{{$venta2->efectivo}}" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Bs.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="pago">Tipo de Pago</label>
                                    <input type="text" class="form-control " id="pago" value="{{$venta2->tipo_pago}}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="ganancia">Ganancia</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control border-info" id="ganancia" value="{{$venta2->ganancia}}" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Bs.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="total">Total Venta</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control border-success bg-success" id="total" value="{{$venta2->total}}" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Bs.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>


                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

                    </div>

                </div>
            </div>
        </div>

    </div>
    @section('css')
    @stop

    @section('js')

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Livewire.on('eliminarVenta', catId => {
            Swal.fire({
                title: 'Esta seguro de eliminar la venta?',
                text: "La eliminacion no se podra revertir!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Eliminar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emitTo('ventas.lista-ventas', 'eliminar', catId)
                    Swal.fire(
                        'Eliminado!',
                        'La venta ha sido eliminada',
                        'success'
                    )
                }
            })

        })
        Livewire.on('alertaRapida', (message) => {
            Swal.fire(
                'Buen trabajo!',
                message,
                'success'
            )
            $('#exampleModal').modal('hide')
        });
    </script>
    @stop
</div>