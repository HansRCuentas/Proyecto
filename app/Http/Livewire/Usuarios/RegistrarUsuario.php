<?php

namespace App\Http\Livewire\Usuarios;

use Livewire\Component;
use Livewire\WithFileUploads;

use Spatie\Permission\Models\Role;
use App\Models\User;
//Para validar la contraseña

use Illuminate\Validation\Rules\Password;

class RegistrarUsuario extends Component
{
    use WithFileUploads;
    public $nombre, $email, $password, $imagen, $role,$identificador;

    public function mount()
    {
        $this->identificador=rand();
    }
    public function render()
    {
        $roles = Role::all();
        return view('livewire.usuarios.registrar-usuario', compact('roles'));
    }
    public function guardarUsuario()
    {
        $rules2 = [
            'nombre' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'max:50',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ];
        $messages = [
            'nombre.required' => 'El nombre es requerido',
            'email.required' => 'El email es requerido',
            'email.email' => 'El email tiene que ser tipo email',
            'password' => 'El password es requerido'
        ];
        $this->validate($rules2, $messages);
        $user = new User;
        $user->name = $this->nombre;
        $user->email = $this->email;
        $user->password = bcrypt($this->password);
        //verifica si hay una imagen
        if ($this->imagen) {
            $imagenCargada = $this->imagen->store('users');
            $user->profile_photo_path = $imagenCargada;
        }
        $role = Role::find($this->role);
        $user->syncRoles($this->role);


        $user->save();
        $this->emitTo('usuarios.lista-usuarios', 'render');
        $this->emit('alertaRapida-success-modalCrear', 'El usuario se creo satisfactoriamente');
    }
    public function resetear()
    {
        $this->identificador = rand();
        $this->reset(['nombre', 'email', 'password', 'imagen']);
        $this->resetErrorBag(['nombre', 'email', 'password']);
    }
}
