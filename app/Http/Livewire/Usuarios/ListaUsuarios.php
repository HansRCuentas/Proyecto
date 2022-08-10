<?php

namespace App\Http\Livewire\Usuarios;


use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;

class ListaUsuarios extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Para editar el Usuario
    public $idUser, $nombre, $email, $password, $imagen,
        $imagen2;
    //Para el role
    public $role;
    //Para resetear el valor de la imagen
    public $identificador;
    //Para buscar
    public $buscar;
    public $listeners = [
        'render' => 'render',
        'eliminar' => 'eliminar'
    ];
    public function mount()
    {
        $this->identificador = rand();
    }
    public function updatingBuscar()
    {
        $this->resetPage();
    }
    public function render()
    {
        $users = User::where('name', 'like', '%' . $this->buscar . '%')
            ->orWhere('email', 'like', '%' . $this->buscar . '%')
            ->paginate(5);
        $roles = Role::all();
        return view('livewire.usuarios.lista-usuarios', compact('users', 'roles'))
            ->extends('adminlte::page')
            ->section('content');
    }
    public function editar(User $user)
    {
        $this->idUser = $user->id;
        $this->nombre = $user->name;
        $this->email = $user->email;
        //Para las imagenes
        $this->imagen = $user->profile_photo_path;
        $this->imagen2 = null;
        $this->reset('password');
        //fin
        if (count($user->roles)) {
            $this->role = $user->roles[0]->id;
        }
        $this->identificador = rand();
    }
    public function modificar()
    {
        $rules2 = [
            'nombre' => 'required',
            'email' => 'required|email',

        ];
        $messages = [
            'nombre.required' => 'El nombre es requerido',
            'email.required' => 'El email es requerido',
        ];
        $this->validate($rules2, $messages);
        $user = User::find($this->idUser);
        $user->name = $this->nombre;
        $user->email = $this->email;
        if ($this->password) {
            $user->password = bcrypt($this->password);
        }
        //modifica con otra imagen si es que la encontro
        if ($this->imagen2) {
            Storage::delete([$user->profile_photo_path]);
            $user->profile_photo_path = $this->imagen2->store('users');
        }
        $user->syncRoles($this->role);
        $user->save();
        $this->emit('alertaRapida-success-modal', 'El usuario se modifico satisfactoriamente');
        $this->resetear();
    }
    public function eliminar(User $user)
    {
        $user->delete();
    }
    public function resetear()
    {
        //resetea valores
        $this->reset(['imagen2', 'nombre', 'email', 'password']);
        $this->resetErrorBag(['nombre', 'email', 'password']);
    }
}
