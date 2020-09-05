<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    protected $guarded = [
        'id'
    ];

    public function members(){
        return $this->belongsToMany(User::class, 'board_members', 'board_id', 'user_id');
    }

    public function lists(){
        return $this->hasMany(BoardList::class);
    }

    public function creator(){
        return $this->belongsTo(User::class);
    }
}
