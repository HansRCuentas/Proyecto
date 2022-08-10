<div>
    @section('title','Inicio')
    <h1><b>Producciones Creativa Vlashey Print</b></h1>
    <div class="card">
        <div class="card-body">

            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="card text-white bg-light mb-3" style="max-width: 18rem;">
                        <div class="card-header h5"><b>Ventas de hoy: {{date('d-m-Y')}}</b> </div>
                        <div class="card-body">
                            <h5>Nro de Ventas: <span class="badge badge-secondary"> {{$nroVentas}} </span></h5>
                            <h5>Total Ventas: <span class="badge badge-secondary">{{$totalVentas}} Bs.</span></h5>
                            <h5>Articulos vendidos: <span class="badge badge-secondary">{{$totalCantidad}} u.</span></h5>
                        </div>
                        <a href="{{route('reporte_ventas')}}">
                            <div role="button" class=" border card-footer bg-light">
                                <div class="d-flex  align-items-center justify-content-between">
                                    <div class="h5">Ver detalles</div>
                                    <i class=" fa-solid fa-circle-chevron-right"></i>
                                </div>

                            </div>
                        </a>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-light mb-3" style="max-width: 18rem;">
                        <div class="card-header h5"><b>Productos Terminados</b> </div>
                        <div class="card-body ">
                            <h5>Total Productos: <span class="badge badge-secondary"> {{$cantidadTotalPT}} u.</span></h5>

                            <h5>En Stock: <span class="badge badge-success"> {{$cantStockT}} u.</span></h5>
                            <h5>Bajo Stock: <span class="badge badge-warning">{{$cantBajoT}} u.</span></h5>
                            <h5>Agotado: <span class="badge badge-danger">{{$cantAgotadoT}} u.</span></h5>
                        </div>
                        <a href="{{route('reporte_productos',2)}}">
                            <div role="button" class=" border card-footer bg-light">
                                <div class="d-flex  align-items-center justify-content-between">
                                    <div class="h5">Ver detalles</div>
                                    <i class=" fa-solid fa-circle-chevron-right"></i>
                                </div>

                            </div>
                        </a>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-light mb-3" style="max-width: 18rem;">
                        <div class="card-header h5"><b>Materia Prima</b> </div>
                        <div class="card-body">
                        <h5>Total Productos: <span class="badge badge-secondary"> {{$cantidadTotalMP}} u.</span></h5>
                            <h5>En Stock: <span class="badge badge-success"> {{$cantStock}} u.</span></h5>
                            <h5>Bajo Stock: <span class="badge badge-warning">{{$cantBajo}} u.</span></h5>
                            <h5>Agotado: <span class="badge badge-danger">{{$cantAgotado}} u.</span></h5>
                        </div>
                        <a href="{{route('reporte_productos',1)}}">
                            <div role="button" class=" border card-footer bg-light">
                                <div class="d-flex  align-items-center justify-content-between">
                                    <div class="h5">Ver detalles</div>
                                    <i class=" fa-solid fa-circle-chevron-right"></i>
                                </div>

                            </div>
                        </a>

                    </div>
                </div>



            </div>

        </div>
    </div>
</div>