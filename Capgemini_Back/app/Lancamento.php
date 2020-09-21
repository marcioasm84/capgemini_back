<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lancamento extends Model
{
    //
    
    public function conta() {
        return $this->belongsTo('App\Conta');
    }
}
