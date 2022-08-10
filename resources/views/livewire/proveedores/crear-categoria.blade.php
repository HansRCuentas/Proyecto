<div>
    <!-- Button trigger modal -->
    <button type="button" 
    class="btn btn-danger form-control" data-toggle="modal" data-target="#exampleModal" 
    >
    Crear Linea
    </button>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear Linea</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" wire:click="resetear">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label for="Nombre">Nombre Linea</label>
                        <input type="text" class="form-control" id="Nombre" wire:model.defer="nombre">
                        @error('nombre')
                        <small class="form-text text-danger">{{$message}}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción Linea</label>
                        <input type="text" class="form-control" id="descripcion" wire:model.defer="descripcion">

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="resetear">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="guardarCategoria">Guardar Linea</button>
                </div>
            </div>
        </div>
    </div>
</div>