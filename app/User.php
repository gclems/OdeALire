<?php

namespace App;
use App;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Authenticable as AuthenticableTrait;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

    protected $table = 'user';
	  protected $primaryKey = 'Id';
    protected $fillable = ['Name', 'Email', 'Password'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'Password', 'remember_token',
    ];

    public function getRememberTokenName()
    {
        return 'RememberToken';
    }

    public function getAuthPassword()
    {
        return $this->Password;
    }
}
