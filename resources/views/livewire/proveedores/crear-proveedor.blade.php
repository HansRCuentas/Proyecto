<div>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger form-control" data-toggle="modal" data-target="#exampleModal">
        Crear Proveedor
    </button>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear Proveedor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" wire:click="resetear">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label for="empresa">Empresa</label>
                        <input type="text" class="form-control" id="empresa" wire:model.defer="empresa">
                        @error('empresa')
                        <small class="form-text text-danger">{{$message}}</small>
                        @enderror
                    </div>


                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="celular">Celular</label>
                            <input type="text" class="form-control" id="celular" wire:model.defer="celular">
                            @error('celular')
                            <small class="form-text text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="nit">Nit</label>
                            <input type="text" class="form-control" id="nit" wire:model.defer="nit">
                            @error('nit')
                            <small class="form-text text-danger">{{$message}}</small>
                            @enderror
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" class="form-control" id="direccion" wire:model.defer="direccion">
                        @error('direccion')
                        <small class="form-text text-danger">{{$message}}</small>
                        @enderror
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
                    <button type="button" class="btn btn-primary" wire:click="guardarProveedor">Guardar Proveedor</button>
                </div>
            </div>
        </div>
    </div>
</div>