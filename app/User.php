<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = "superusers";
    protected $fillable = [
    	'name',
    	'email',
    	'username',
    	'password',
    	'division',
    	'is_superuser',
    	'is_active',
    	'updated_by',
    	'created_by',
    	'deleted_by'
    ];

    const IS_SUPERUSER = [
        1 => 'Superuser',
        0 => 'User'
    ];

    const IS_ACTIVE = [
        1 => 'Active',
        0 => 'Non Active'
    ];

    public function is_superuser(){
        return (object) self::IS_SUPERUSER[$this->is_superuser];
    }
    public function is_active(){
        return (object) self::IS_ACTIVE[$this->is_active];
    }
}
