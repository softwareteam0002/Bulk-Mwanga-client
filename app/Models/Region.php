<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    //

    public function region(){

        return $this->hasMany('App\Models\District');
    }
}
