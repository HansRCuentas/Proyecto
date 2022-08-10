<div>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger form-control" data-toggle="modal" data-target="#modalCrear">
        Crear Cliente
    </button>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear Cliente</h5>
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
                                <label for="Nombre">Direccion</label>
                                <input type="text" class="form-control" id="Nombre" wire:model.defer="direccion">
                                @error('direccion')
                                <small class="form-text text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="region">Region</label>
                                <select class="custom-select" size="4" wire:model="region">
                                    <option selected value="">Selecciona la region</option>
                                    <option value="LA PAZ">LA PAZ</option>
                                    <option value="EL ALTO">EL ALTO</option>
                                    <option value="ORURO">ORURO</option>
                                    <option value="POTOSI">POTOSI</option>
                                    <option value="CHUQUISACA">CHUQUISACA</option>
                                    <option value="TARIJA">TARIJA</option>
                                    <option value="BENI">BENI</option>
                                    <option value="PANDO">PANDO</option>
                                    <option value="SANTA CRUZ">SANTA CRUZ</option>
                                </select>
                                @error('region')
                                <small class="form-text text-danger">{{$message}}</small>
                                @enderror
                            </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="celular">Celular</label>
                            <input type="text" class="form-control" id="celular" wire:model.defer="celular">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="cedula">Cedula</label>
                            <input type="text" class="form-control" id="cedula" wire:model.defer="cedula">
                        </div>
                    </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="resetear">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="guardarCliente">Guardar Cliente</button>
                </div>
            </div>
        </div>
    </div>
</div>