<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BoardList extends Model
{
    protected $guarded = [
        'id'
    ];

    public function cards(){
        return $this->hasMany(Card::class, 'list_id');
    }
}
