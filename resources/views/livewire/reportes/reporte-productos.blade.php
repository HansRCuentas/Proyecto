<div>
    {{$estado}}
    @section('title', 'Reporte Ventas')
    <h1><b>Reporte de Productos</b></h1>
    <div class="card">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="cliente">ELIGE EL LISTADO DE PRODUCTOS</label>
                    <select class="form-control" wire:model="tipo">
                        <option value="2" selected>Productos Terminados</option>
                        <option value="1">Materia Prima</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="cliente">LISTAR PRODUCTOS</label>
                    <select class="form-control" wire:model="estado">
                        <option value="0" selected>Todos</option>
                        <option value="1" >En Stock</option>
                        <option value="2">Stock Bajo</option>
                        <option value="3">Agotado</option>
                    </select>
                    
                </div>
                <div class="form-group col-md-2 text-center">
                    <label for="fecha">EN STOCK</label>
                    <h4><span class="badge badge-success">{{$cantStock}} Items</span></h4>
                </div>
                <div class="form-group col-md-2 text-center">
                    <label for="">BAJO STOCK</label>
                    <h4><span class="badge badge-info">{{$cantBajo}} Items</span></h4>
                </div>
                <div class="form-group col-md-2 text-center">
                    <label for="">AGOTADO</label>
                    <h4><span class="badge badge-danger">{{$cantAgotado}} Items</span></h4>
                </div>
            </div>
            @if(count($productos))
            <!-- TAAAAABLAAAAAAAAAAA -->
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="thead-dark text-center align-middle">
                        <tr>
                            <th scope="col">NRO. CODIGO</th>
                            <th scope="col">PRODUCTO</th>
                            <th scope="col">CATEGORIA</th>
                            <th scope="col">DISPONIBILIDAD</th>
                            <th scope="col">STOCK</th>
                            <th scope="col">STOCK MINIMO</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">

                        @foreach ($productos as $item)
                        <tr>
                            <td>{{$item->nro_codigo}}</td>
                            <td>{{$item->nombre}}</td>
                            <td>{{$item->categoria->nombre}}</td>
                           
                            <td>
                                @if ($item->stock > $item->stock_minimo)
                                <h5><span class="badge badge-success">En Stock</span></h5>
                                @else
                                @if($item->stock <=0 )
                                <h5><span class="badge badge-danger">Agotado</span></h5>
                                @else
                                <h5><span class="badge badge-info">Bajo Stock</span></h5>
                                @endif
                                @endif
                            </td>
                            
                            <td>{{$item->stock}}</td>
                            <td>{{$item->stock_minimo}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if ($productos->hasPages())

                <div class="row justify-content-center">
                    {{$productos->links()}}
                </div>
                @endif

            </div>
            <!-- FIN DE LA TABLA -->
            @else
            <div class="alert alert-warning" role="alert">
                <strong>No se tienen Registros</strong>
            </div>
            @endif
        </div>
    </div>
</div>