<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Venta;
use App\Models\Compra;
use App\Models\Salida;
use App\Models\Ingreso;

class User extends Authenticatable
{
    use HasRoles;
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    public $guard_name = 'web';
    public function guardName()
    {
        return 'web';
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];
    //Para agregar imagen
    public function adminlte_image(){
        if(strlen(Auth()->user()->profile_photo_path)>0){
            return asset('storage/'.Auth()->user()->profile_photo_path);
        }
        return 'https://picsum.photos/300/300';
        
    }
    public function adminlte_desc(){
        return 'Administrador';
    }
    public function adminlte_profile_url(){
        return 'perfil/username'; 
    }
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
    public function compras()
    {
        return $this->hasMany(Compra::class);
    }
    public function salidas()
    {
        return $this->hasMany(Salida::class);
    }
    public function ingresos()
    {
        return $this->hasMany(Ingreso::class);
    }
}
