<div>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger form-control" data-toggle="modal" data-target="#modalCrear" wire:click="resetear">
        Crear Usuario
    </button>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear Usuario</h5>
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
                        <label for="email">Email</label>
                        <input type="text" class="form-control" id="email" wire:model.defer="email">
                        @error('email')
                        <small class="form-text text-danger">{{$message}}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="codigo">Contraseña</label>
                        <input type="password" class="form-control" id="codigo" wire:model.defer="password">
                        @error('password')
                        <small class="form-text text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="Imagen">Imagen</label>
                        <input type="file" class="form-control-file" id="{{$identificador}}" wire:model.defer="imagen">
                    </div>
                    <div wire:loading wire:target="imagen" class="alert alert-danger" role="alert">
                        <strong>Cargando Imagen!</strong> Espere un momento hasta que la imagen se haya procesado
                    </div>
                    @if ($imagen)
                    <img src="{{ $imagen->temporaryUrl() }}" class="img-thumbnail">
                    @endif

                    <div class="form-group">
                        <label for="categorias">Roles</label>
                        <select class="custom-select" wire:model.defer="role">
                            <option value="" selected>--Seleccione Un Rol--</option>
                            @foreach($roles as $rol)
                            <option value="{{$rol->id}}">{{$rol->name}}</option>
                            @endforeach
                        </select>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="resetear">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="guardarUsuario">Guardar Usuario</button>
                </div>

            </div>
        </div>
    </div>
</div>