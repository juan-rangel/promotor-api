<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
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
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    // /**
    //  * Retrieve the model for a bound value.
    //  *
    //  * @param  mixed  $value
    //  * @param  string|null  $field
    //  * @return \Illuminate\Database\Eloquent\Model|null
    //  */
    // public function resolveRouteBinding($value, $field = null)
    // {
    //     dd(request()->filled('email'));
    //     dd(parent::resolveRouteBinding($value, $field = null));
    //     // dd($this->getRelationValue(1));
    //     // dd($this->getRouteKeyName(), $field, $value);
    //     return parent::resolveRouteBinding($value, $field = null);
    //     // dd($value, $field);
    //     // return $this->where('name', $value)->firstOrFail();
    // }

}
