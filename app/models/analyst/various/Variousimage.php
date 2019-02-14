<?php

namespace App\models\analyst\various;

use Illuminate\Database\Eloquent\Model;

class Variousimage extends Model
{
    protected $fillable = ['image','thumbnail' ];
    public function article (  ) {
        return $this->belongsTo('App\models\analyst\various\Variousarticle');
    }
}
