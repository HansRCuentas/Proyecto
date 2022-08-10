<div>
    @section('title', 'Pendientes')
    <h1><b>Lista de Ventas Pendientes</b></h1>
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
                            <th scope="col" role="button" class="align-middle">SALDO PENDIENTE</th>
                            <th scope="col" role="button" class="align-middle">GANANCIA ESPERADA</th>
                            <th scope="col" role="button" class="align-middle">REGION</th>
                            <th scope="col" role="button" class="align-middle">CLIENTE</th>
                            <th scope="col" role="button" class="align-middle">USUARIO</th>
                            <th scope="col" role="button" class="align-middle" width="150">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ventas as $venta)
                        <tr>
                            <th scope="row" class="text-center">{{$venta->nro_venta}}</th>
                            <td>{{$venta->tipo_pago}}</td>
                            <td>{{$venta->fecha}}</td>
                            <td>{{$venta->aumento. " Bs."}}</td>
                            <td>{{$venta->descuento. " Bs."}}</td>
                            <td>{{$venta->efectivo. " Bs."}}</td>
                            <td>
                                <h5><span class="badge badge-success">{{$venta->total." Bs."}}</span></h5>
                            </td>
                            <td class="bg-info">{{$venta->total-$venta->efectivo." Bs."}}</td>
                            <td>
                                <h5><span class="badge badge-info">{{$venta->ganancia." Bs."}}</span></h5>
                            </td>
                            <td>{{$venta->region}}</td>
                            <td>{{$venta->cliente->nombre}}</td>
                            <td>{{$venta->user->name}}</td>

                            <td>
                                <div class="row justify-content-center">
                                    <a class="btn btn-success" wire:click="descargarPdf({{$venta->id}})"><i class="fa-solid fa-file-pdf"></i></a>
                                    <a class="btn btn-warning" data-toggle="modal" data-target="#modalEditar" wire:click="editar({{$venta->id}})"><i class="fa-solid fa-dollar-sign"></i></a>
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
                        <h5 class="modal-title" id="exampleModalLabel">Detalle de Venta Pendiente</h5>
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
                                    <input type="text" class="form-control " id="nro" value="{{$ventap->nro_venta}}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="fecha">Fecha</label>
                                    <input type="text" class="form-control " id="fecha" value="{{$ventap->fecha}}">

                                </div>

                                <div class="form-group col-md-4">
                                    <label for="region">Region</label>
                                    <input type="text" class="form-control " id="region" value="{{$ventap->region}}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-7">
                                    <label for="cliente">Cliente</label>

                                    <input type="text" class="form-control " id="cliente" value="{{$cliente->nombre. ' ' . $cliente->cedula}}">

                                </div>
                                <div class="form-group col-md-5">
                                    <label for="user">Usuario</label>
                                    <input type="text" class="form-control " id="user" value="{{$user->name}}">

                                </div>
                            </div>

                        </fieldset>
                        @if (count($ventap->productos))
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
                                    @foreach ($ventap->productos as $prod)
                                    <tr>

                                        <td>{{$prod->nombre}}</td>
                                        <td>{{$prod->precio_venta}}
                                        </td>
                                        <td>{{$prod->pivot->cantidad}}</td>
                                        <td>{{$prod->precio_venta*$prod->pivot->cantidad}}</td>

                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><strong>TOTAL PRODUCTOS:</strong></td>
                                        <td><strong>{{$total_medio}}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @endif
                        <fieldset disabled>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="aumentod">Aumento</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="aumentod" value="{{$ventap->aumento}}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Bs.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="descuentod">Descuento</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="descuentod" value="{{$ventap->descuento}}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Bs.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="montod">Monto Cancelado</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="montod" value="{{$ventap->efectivo}}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Bs.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="pago">Tipo de Pago</label>
                                    <input type="text" class="form-control " id="pago" value="{{$ventap->tipo_pago}}">

                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="gananciad">Ganancia</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control border-info" id="gananciad" value="{{$ventap->ganancia}}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Bs.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="saldod">Saldo Pendiente</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control border-info bg-info" id="saldod" value="{{$ventap->total-$ventap->efectivo}}" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Bs.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-4 ">
                                    <label for="totald">Total Venta</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control border-info bg-success" id="totald" value="{{$ventap->total}}" readonly>
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
        <!-- Modal para Registrar un pago -->
        <div wire:ignore.self class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detalles de Pago</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div wire:loading wire:target="editar" class="text-center text-primary">
                        <div class="spinner-border" role="status">
                        </div>
                    </div>
                    <div class="modal-body container" wire:loading.remove wire:target="editar">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="nro">Nro Venta</label>
                                <input type="text" class="form-control " id="nro" wire:model.defer="nro_venta" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fecha">Fecha</label>
                                <input type="text" class="form-control " id="fecha" wire:model.defer="fecha" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="monto">Monto Cancelado</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="monto" wire:model.defer="monto_cancelado" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Bs.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="total">Total</label>
                                <div class="input-group">
                                    <input type="text" class="form-control bg-success" id="total" wire:model.defer="total_venta" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Bs.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="saldo">Saldo Pendiente</label>

                                <div class="input-group">
                                    <input type="text" class="form-control" id="saldo" wire:model.defer="saldo" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Bs.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row justify-content-center">
                            <div class="form-group col-md-4">
                                <label for="monto">Monto a Pagar</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="monto" wire:model="efectivo" placeholder="0">
                                    <div class="input-group-append">
                                        <span class="input-group-text">Bs.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="cambiop">Cambio</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="cambiop" wire:model="cambio" placeholder="0" disabled>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Bs.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" wire:click="cancelarPago">Registrar Pago</button>
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
                    Livewire.emitTo('ventas.listar-ventas-pendientes', 'eliminar', catId)
                    Swal.fire(
                        'Eliminado!',
                        'La venta ha sido eliminada',
                        'success'
                    )
                }
            })

        })
        Livewire.on('alertaRapida', (message) => {

            Swal.fire({
                position: 'top',
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 1500
            })
            $('#modalEditar').modal('hide')
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
    </script>
    @stop
</div>