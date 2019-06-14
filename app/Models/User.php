<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *-a
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','api_token',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class,'roles_users');
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class,'modules_users');
    }

    public function hasRoles(array $roles)
    {
        foreach ($roles as $role)
        {
            foreach ($this->roles as $userRole)
            {
                if ($userRole->fullname === $role)
                {
                    return true;
                }
            }
        }
        return false;
    }
}
