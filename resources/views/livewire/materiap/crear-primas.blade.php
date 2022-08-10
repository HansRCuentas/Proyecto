<div>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger form-control" data-toggle="modal" data-target="#modalCrear">
        Crear Producto
    </button>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" wire:click="resetear">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" wire:model.defer="nombre">
                        @error('nombre')
                        <small class="form-text text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripcion</label>
                        <input type="text" class="form-control" id="descripcion" wire:model.defer="descripcion">
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="codigo">Nro de Codigo</label>
                            <input type="text" class="form-control" id="codigo" wire:model.defer="nro_codigo">
                            @error('nro_codigo')
                            <small class="form-text text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="costo">Costo</label>
                            <input type="number" class="form-control" id="costo" wire:model.defer="costo_producto">
                            @error('costo_producto')
                            <small class="form-text text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="stock">Stock</label>
                            <input type="number" class="form-control" id="stock" wire:model.defer="stock">
                            @error('stock')
                            <small class="form-text text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="stockmin">Stock Minimo</label>
                            <input type="number" class="form-control" id="stockmin" wire:model.defer="stock_minimo">
                            @error('stock_minimo')
                            <small class="form-text text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
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

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="resetear">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="guardarProducto">Guardar Producto</button>
                </div>

            </div>
        </div>
    </div>