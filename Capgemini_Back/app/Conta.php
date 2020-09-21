<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conta extends Model
{
    //
    
    public function lancamentos() {
        return $this->hasMany('App\Lancamento');
    }
}
