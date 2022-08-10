<div>
    @section('title', 'Compras Pendientes')
    <h1><b>Lista de Compras Pendientes</b></h1>
    <div class="card">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-10">
                    <input type="text" class="form-control" placeholder="Ingresa el texto a buscar" wire:model="buscar">
                </div>
                <div class="form-group col-md-2">
                    <a class="btn btn-danger form-control" href="{{route('registro_compras')}}">Registrar Compra</a>
                </div>
            </div>
            @if (count($compras))
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="thead-dark text-center align-middle">
                        <tr>
                            <th scope="col" role="button" class="align-middle" wire:click="ordenar('nro_compra')">
                                NRO
                                @if ($ordenar_por == 'nro_compra')
                                @if ($direccion == 'asc')
                                <i class="fa-solid fa-arrow-down-1-9 float-right mt-1"></i>
                                @else
                                <i class="fa-solid fa-arrow-down-9-1  float-right mt-1"></i>
                                @endif
                                @else
                                <i class="fas fa-sort float-right mt-1"></i>
                                @endif
                            </th>
                            <th scope="col" role="button" class="align-middle">TIPO PAGO
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
                            <th scope="col" role="button" class="align-middle">PROVEEDOR</th>

                            <th scope="col" role="button" class="align-middle">EFECTIVO</th>
                            <th scope="col" role="button" class="align-middle">TOTAL</th>
                            <th scope="col" role="button" class="align-middle">SALDO</th>
                            <th scope="col" role="button" class="align-middle">ACCIONES</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($compras as $item)
                        <tr>
                            <th scope="row" class="text-center">{{$item->id}}</th>
                            <td>
                                @if ($item->tipo_pago==2)
                                CREDITO
                                @endif
                                @if ($item->tipo_pago==3)
                                ADELANTO
                                @endif
                            </td>
                            <td>{{$item->fecha}}</td>
                            <td>{{$item->user->name}}</td>
                            <td>

                                <strong>Empresa: </strong>{{$item->proveedor->empresa }} <br>
                                <strong>Celular:</strong> {{$item->proveedor->celular }} <br>
                                <strong>Linea:</strong> {{$item->proveedor->categoria->nombre}} <br>


                            <td>
                                {{$item->efectivo." Bs."}}
                            </td>
                            <td>
                                <h5><span class="badge badge-success">{{$item->total." Bs."}}</span></h5>
                            </td>
                            <td class="bg-info">{{$item->total-$item->efectivo." Bs."}}</td>
                            <td>
                                <div class="row  justify-content-center">
                                    <a class="btn btn-warning" data-toggle="modal" data-target="#modalPago" wire:click="pagar({{$item->id}})"><i class="fa-solid fa-dollar-sign"></i></a>
                                    <a class="btn btn-primary" data-toggle="modal" data-target="#modalMostrar" wire:click="mostrar({{$item->id}})"><i class="fa-solid fa-rectangle-list"></i></a>
                                    <a class="btn btn-danger" wire:click="$emit('eliminarCompra',{{$item->id}})"><i class="fa-solid fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            @if ($compras->hasPages())

            <div class="row justify-content-center">
                {{$compras->links()}}
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

    <!-- Modal para Registrar un pago -->
    <div wire:ignore.self class="modal fade" id="modalPago" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detalles de Pago</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div wire:loading wire:target="pagar" class="text-center text-primary">
                    <div class="spinner-border" role="status">
                    </div>
                </div>
                <div class="modal-body container" wire:loading.remove wire:target="pagar">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nro">Nro Compra</label>
                            <input type="text" class="form-control " id="nro" wire:model.defer="nro_comprap" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="fecha">Fecha</label>
                            <input type="text" class="form-control " id="fecha" wire:model.defer="fechap" readonly>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="monto">Monto Cancelado</label>
                            <div class="input-group">
                                <input type="text" class="form-control" wire:model='efectivop' readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">Bs.</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="total">Total</label>
                            <div class="input-group">
                                <input type="text" class="form-control bg-success" wire:model='totalp' readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">Bs.</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="saldo">Saldo Pendiente</label>
                            <div class="input-group">
                                <input type="text" class="form-control bg-info" wire:model='saldop' readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">Bs.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-5">
                            <label for="monto">Monto a Pagar</label>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <button class="btn btn-danger form-control" wire:click='ajustar'>Ajustar</button>
                                </div>
                                <div class="form-group col-md-8">
                                    <div class="input-group">
                                        <input type="number" class="form-control" placeholder="0" wire:model='montop'>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Bs.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="form-group col-md-4">
                            <label for="total">Cambio</label>
                            <div class="input-group">
                                <input type="number" class="form-control" placeholder="0" wire:model='cambiop' readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">Bs.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="registrarPago">Registrar Pago</button>
                </div>

            </div>
        </div>
    </div>
    <!-- Fin modal para Registrar un pago -->
    <!-- Modal para mostrar el detalle de venta -->
    <div wire:ignore.self class="modal fade" id="modalMostrar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detalle de Compra Pendiente</h5>
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
                                <label for="nro">Nro Compra</label>
                                <input type="text" class="form-control " id="nro" value="{{$comprap->nro_compra}}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="fecha">Fecha</label>
                                <input type="text" class="form-control " id="fecha" value="{{$comprap->fecha}}">

                            </div>

                            <div class="form-group col-md-4">
                                <label for="region">Tipo Pago</label>
                                @php
                                $tipo_p="";
                                @endphp
                                @if($comprap->tipo_pago==2)
                                @php
                                $tipo_p="CREDITO";
                                @endphp


                                @endif
                                @if($comprap->tipo_pago==3)
                                @php
                                $tipo_p="ADELANTO";
                                @endphp

                                @endif

                                <input type="text" class="form-control " id="region" value="{{$tipo_p}}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-7">
                                <label for="cliente">Usuario</label>

                                <input type="text" class="form-control " id="cliente" value="{{$usuario->name}}">

                            </div>
                            <div class="form-group col-md-5">
                                <label for="user">Proveedor</label>
                                <input type="text" class="form-control " id="user" value="{{$proveedor->empresa. ' - Celular: '.$proveedor->celular }}">

                            </div>
                        </div>

                    </fieldset>
                    @if (count($comprap->productos))
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
                                @foreach ($comprap->productos as $prod)
                                <tr>

                                    <td>{{$prod->nombre}}</td>
                                    <td>{{$prod->costo_producto}}
                                    </td>
                                    <td>{{$prod->pivot->cantidad}}</td>
                                    <td>

                                        <div class="input-group">
                                            <input type="text" class="form-control bg-secondary" value="{{$prod->costo_producto*$prod->pivot->cantidad}}" readonly>
                                            <div class="input-group-append">
                                                <span class="input-group-text">Bs.</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><strong>TOTAL:</strong></td>
                                    <td>
                                        <strong>
                                            <div class="input-group">
                                                <input type="text" class="form-control bg-success" value="{{$comprap->total}}" readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Bs.</span>
                                                </div>
                                            </div>
                                        </strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @endif
                    <fieldset disabled>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="efectivo">Efectivo</label>
                                <div class="input-group">
                                    <input type="text" class="form-control border-info" id="efectivo" value="{{$comprap->efectivo}}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Bs.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="total">Total Venta</label>
                                <div class="input-group">
                                    <input type="text" class="form-control border-info bg-success" id="total" value="{{$comprap->total}}" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Bs.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4 ">
                                <label for="saldo">Saldo Pendiente</label>
                                <div class="input-group">
                                    <input type="text" class="form-control border-info bg-info" id="saldo" value="{{$comprap->total-$comprap->efectivo}}" readonly>
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
    <!-- fin de modal para mostrar el detalle de venta -->





    @section('js')

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Livewire.on('eliminarCompra', catId => {
            Swal.fire({
                title: 'Esta seguro de eliminar la compra?',
                text: "La eliminacion no se podra revertir!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Eliminar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emitTo('compras.compras-pendientes', 'eliminar', catId)
                    Swal.fire(
                        'Eliminado!',
                        'La compra ha sido eliminada',
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
            $('#modalPago').modal('hide')
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