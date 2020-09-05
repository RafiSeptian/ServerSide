<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
       'id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['initial'];

    public function getInitialAttribute(){
        $first = $this->attributes['first_name'];
        $last = $this->attributes['last_name'];

        $full_name = $first .' '.$last;

        $ex = explode(' ', $full_name);

        $initial = '';

        foreach($ex as $word){
            $initial .= strtoupper($word[0]);
        }

        return $initial;
    }

    public function token(){
        return $this->hasOne(LoginToken::class);
    }

    public function board(){
        return $this->hasMany(Board::class);
    }
}
