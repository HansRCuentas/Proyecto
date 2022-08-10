<div>
    @section('title', 'Reporte Ventas')
    <h1><b>Reporte de ventas</b></h1>
    <div class="card">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="cliente">ELIGE EL TIPO DE REPORTE</label>
                    <select class="form-control" wire:model.defer="sw">
                        <option value="1" selected>Ventas del dia</option>
                        <option value="2">Ventas por fecha</option>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="nro">FECHA INICIO</label>
                    <input type="date" class="form-control  text-center" id="nro" wire:model.defer="desde">
                </div>
                <div class="form-group col-md-2">
                    <label for="fecha">FECHA FINAL</label>
                    <input type="date" class="form-control  text-center" id="nro" wire:model.defer="hasta">
                </div>
                <div class="form-group col-md-2 text-white">
                    <label for="">Consultar</label>
                    <input type="button" class="form-control btn btn-danger" value="Consultar" id="" wire:click="consultar">
                </div>
            </div>
            <!-- TAAAAABLAAAAAAAAAAA -->
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="thead-dark text-center align-middle">
                        <tr>
                            <th scope="col" class="justify-content">TOTAL VENTA</th>
                            <th scope="col">GANANCIA</th>
                            <th scope="col">CANT. PRODUCTOS</th>
                            <th scope="col">ESTADO</th>
                            <th scope="col">USUARIO</th>
                            <th scope="col">FECHA</th>
                            <th scope="col">ACCIONES</th>
                        </tr>
                    </thead>

                    @if (count($ventas))
                    <tbody>

                        @foreach ($ventas as $item)
                        <tr>
                            <td class="text-center">
                                <h5><span class="badge badge-secondary">Bs.{{$item->total}}</span></h5>
                            </td>
                            <td class="text-center">
                                <h5><span class="badge badge-success">Bs.{{$item->ganancia}}</span></h5>
                            </td>
                            <td class="text-center">
                                @php
                                $cant=0;
                                @endphp
                                @foreach ($item->productos as $prod)
                                @php
                                $cant += intval($prod->pivot->cantidad);
                                @endphp
                                @endforeach

                                {{$cant}}

                            </td>
                            <td>{{$item->tipo_pago}}</td>
                            <td>{{$item->user->name}}</td>
                            <td>{{\Carbon\Carbon::parse($item->fecha)->format('d-m-Y')}}</td>
                            <td>
                                <div class="row  justify-content-center">
                                    <!-- Boton para eliminar del carrito-->
                                    <a class="btn btn-info" data-toggle="modal" data-target="#modalMostrar" wire:click="mostrar({{$item->id}})"><i class="fa-solid fa-rectangle-list"></i> </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                    @endif


                </table>
                @if ($ventas->hasPages())

                <div class="row justify-content-center">
                    {{$ventas->links()}}
                </div>
                @endif

            </div>

            <div class="form-row justify-content-center">
                <div class="form-group col-md-2">
                    <label for="totalVentas">VENTAS TOTALES</label>
                    <input type="text" class="form-control  text-center bg-success" id="totalVentas" value="{{'Bs. ' . $totalVentas}}" placeholder="0" readonly>
                </div>
                <div class="form-group col-md-2">
                    <label for="totalGanancia">GANANCIAS TOTALES</label>
                    <input type="text" class="form-control  text-center bg-info" id="totalGanancia" value="{{'Bs ' . $totalGanancia}}" placeholder="0" readonly>
                </div>
                <div class="form-group col-md-2">
                    <label for="fecha">PRODUCTOS VENDIDOS</label>
                    <input type="totalCantidad" class="form-control  text-center bg-secondary" id="totalCantidad" value="{{$totalCantidad}}" placeholder="0" readonly>
                </div>
            </div>

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
                                        <input type="text" class="form-control" value="{{$venta2->aumento}}" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Bs.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="descuento">Descuento</label>
                                   
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="{{$venta2->descuento}}" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Bs.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="monto">Monto Cancelado</label>
                                    
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="{{$venta2->efectivo}}" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Bs.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="pago">Tipo de Pago</label>
                                    <input type="text" class="form-control " id="usuario" value="{{$venta2->tipo_pago}}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="ganancia">Ganancia</label>
                                   
                                    <div class="input-group">
                                        <input type="text" class="form-control border-info" value="{{$venta2->ganancia}}" readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Bs.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="total">Total Venta</label>
                                    
                                    <div class="input-group">
                                        <input type="text" class="form-control border-success bg-success" value="{{$venta2->total}}" readonly>
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
    @section('js')

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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