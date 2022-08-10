<div>
    @section('title','CatalogoAdmin')
    <h1><b>Administración de catalogo</b></h1>
    <div class="card">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-10">
                    <input type="text" class="form-control" placeholder="Ingresa el texto a buscar" wire:model="buscar">
                </div>

                <select class="form-control bg-danger col-md-2" wire:model="buscar">
                    <option selected value="" class="text-center">BUSCAR POR CATEGORÍA</option>
                    @foreach ($categorias as $item)
                    <option value="{{$item->nombre}}" class="text-center">{{$item->nombre}}</option>
                    @endforeach
                </select>
            </div>
            @if(count($productos)>0)
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="thead-dark text-center">
                        <tr>
                            <th role="button" scope="col" class="align-middle">PRODUCTO</th>
                            <th role="button" scope="col" class="align-middle">IMAGEN</th>
                            <th role="button" scope="col" class="align-middle">DESCRIPCION</th>
                            <th role="button" scope="col" class="align-middle">CATEGORIA</th>
                            <th role="button" scope="col" class="align-middle">PRECIOS</th>
                            <th role="button" scope="col" class="align-middle">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productos as $item)
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="{{$item->id}}" wire:change="agregarPagina({{$item->id}})" {{$item->indicador == 1 ? 'checked' : ''}}>
                                    <label class="form-check-label" for="{{$item->id}}">
                                        {{$item->nombre}}
                                    </label>
                                </div>

                            </td>
                            <td>
                                <img src="{{asset('storage/'.$item->imagen)}}" width="120" alt="">
                            </td>
                            <td>{{$item->descripcion}}</td>
                            <td>{{$item->categoria->nombre}}</td>
                            <td>
                                <b>Precio por Unidad:</b> {{$item->precio_venta}}
                                <dl>
                                    @foreach ($item->precios as $precio)
                                    <li><b>Mayor: </b> {{$precio->cantidad}} u. <b>Precio:</b> {{$precio->precio}} Bs.</li>
                                    @endforeach
                                </dl>
                            </td>
                            <td>
                                <div class="row justify-content-center">
                                    <a wire:click="agregarPrecios({{$item->id}})" class="btn btn-info" data-toggle="modal" data-target="#modalAgregar"><i class="fa-solid fa-dollar-sign"></i></a>
                                    <a wire:click="editar({{$item->id}})" class="btn btn-success ml-2" data-toggle="modal" data-target="#modalEditar"><i class="fa-solid fa-pen-to-square"></i></a>

                                </div>

                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            @if ($productos->hasPages())

            <div class="row justify-content-center">
                {{$productos->links()}}
            </div>
            @endif
            @else
            <div class="alert alert-warning" role="alert">
                <strong>No se encontraron Registros</strong>
            </div>
            @endif

        </div>
    </div>
    <!-- Modal Agregar-->
    <div wire:ignore.self class="modal fade" id="modalAgregar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Precios</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div wire:loading wire:target="agregarPrecios" class="text-center text-primary">
                    <div class="spinner-border" role="status">
                    </div>
                </div>

                <div class="modal-body" wire:loading.remove wire:target="agregarPrecios">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nombre">Producto</label>
                            <input type="text" class="form-control" id="nombre" wire:model.defer="nombre_prod" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="preciou">Precio por unidad</label>
                            <input type="email" class="form-control" id="preciou" wire:model.defer="precio_unidad" readonly>

                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="cantidad">Cantidad Mayor</label>
                            <input type="number" class="form-control" id="cantidad" wire:model.defer="cantidad" placeholder="Ej. 100 u.">
                            @error('cantidad')
                            <small class="form-text text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="precio">Precio</label>
                            <input type="number" class="form-control" id="precio" wire:model.defer="precio" placeholder="Ej. 5 Bs">
                            @error('precio')
                            <small class="form-text text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="form-control btn btn-success" wire:click="guardarPrecio({{$idprod}})">Agregar Precio</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th role="button" scope="col">CANTIDAD</th>
                                    <th role="button" scope="col">PRECIO</th>
                                    <th role="button" scope="col">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach ($precios as $item)
                                <tr>

                                    <td>{{$item->cantidad}} u.</td>
                                    <td> Bs. {{$item->precio}} </td>

                                    <td>
                                        <div class="row d-block">
                                            <!-- Button trigger modal Para Editar-->
                                            <a wire:click="$emit('eliminarPrecio',{{$item->id}})" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                                        </div>

                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>


            </div>
        </div>
    </div>
    <!-- Modal Editar-->
    <div wire:ignore.self class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div wire:loading wire:target="editar" class="text-center text-primary">
                    <div class="spinner-border" role="status">
                    </div>
                </div>

                <div class="modal-body" wire:loading.remove wire:target="editar">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" wire:model.defer="nombre">
                            @error('nombre')
                            <small class="form-text text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="precio">Precio Venta</label>
                            <input type="number" class="form-control" id="precio" placeholder="0" wire:model.defer="precio_venta">
                            @error('precio_venta')
                            <small class="form-text text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripcion</label>
                        <input type="text" class="form-control" id="descripcion" wire:model.defer="descripcion">
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
                        <label for="categorias">Categoria</label>
                        <select class="custom-select" wire:model.defer="categoria_id">
                            <option value="" selected>--Seleccione Categoria--</option>
                            @foreach($categorias as $cat)
                            <option value="{{$cat->id}}">{{$cat->nombre}}</option>
                            @endforeach
                        </select>
                        @error('categoria_id')
                        <small class="form-text text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" wire:click="modificar">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>





    </div>
    @section('js')

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Livewire.on('eliminarPrecio', catId => {
            Swal.fire({
                title: 'Esta seguro de eliminar el precio?',
                text: "La eliminacion no se podra revertir!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Eliminar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emitTo('productos.productos', 'eliminarPre', catId)
                    Swal.fire(
                        'Eliminado!',
                        'El precio ha sido eliminado',
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
        Livewire.on('ocultarEditar', (message) => {
                Swal.fire(
                    'Buen trabajo!',
                    message,
                    'success'
                )
                $('#modalEditar').modal('hide')
            });
    </script>
    @stop
</div>