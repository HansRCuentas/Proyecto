<?php

namespace App\Http\Livewire\Asignar;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use DB;

class AsignarPermisos extends Component
{
    use WithPagination;
    public $role,$permisosSelected=[] ,$old_permissions=[];
    public $guard_name = 'web';
    protected $listeners=['revokeall'=>'RemoveAll'];
    public function mount()
    {
        $this->role='Elegir';

    }
    public function render()
    {
        $roles=Role::All();
        $permisos=Permission::select('descripcion','name','id',DB::raw("0 as checked"))
        ->orderBy('name','asc')
        ->paginate(6);
        //Se escoge el rol y se cargan sus permisos a la tabla
        if($this->role != 'Elegir'){
            $list=Permission::join('role_has_permissions as rp','rp.permission_id','permissions.id')
            ->where('role_id',$this->role)->pluck('permissions.id')->toArray();
            $this->old_permissions=$list;
        }
        //Los permisos cargados se ponen en valor de checked
        if($this->role != 'Elegir'){
            foreach($permisos as $permiso){
                $role=Role::find($this->role);
                $tienePermiso=$role->hasPermissionTo ($permiso->name);
                if($tienePermiso){
                    $permiso->checked = 1;
                }
            }
        }
        
        return view('livewire.asignar.asignar-permisos',compact('roles','permisos'))
        ->extends('adminlte::page')
        ->section('content');
    }
    public function RemoveAll($id)
    {
        //Quita todos los permisos
        if($this->role == 'Elegir'){
            $this->emit('alertaRapida-error','Seleccione un rol');
            return;
        }
        $role=Role::find($this->role);
        $role->syncPermissions([0]);
    }
    public function SyncAll()
    {
        //Sincroniza todos los permisos
        if($this->role=='Elegir'){
            $this->emit('alertaRapida-error','Seleccione un rol');
            return;
        }
        $role=Role::find($this->role);
        $permisos=Permission::pluck('id')->toArray();
        $role->syncPermissions($permisos);
        $this->emit('alertaRapida-success','Se sincronizaron todos los permisos al Rol');
        
    }
    public function SyncPermiso($state,$permisoName)
    {
        //Sincroniza un solo permiso
        if($this->role != 'Elegir'){
            $roleName= Role::find($this->role);
            if($state){
                $roleName->givePermissionTo($permisoName);
                $this->emit('alertaRapida-success','Permiso asignado correctamente');
                
            }else{
                $roleName->revokePermissionTo($permisoName);
                $this->emit('alertaRapida-success','Permiso revocado correctamente');
                
            }
        }
    }
}
