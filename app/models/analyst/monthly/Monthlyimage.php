<?php

namespace App\models\analyst\monthly;

use Illuminate\Database\Eloquent\Model;

class Monthlyimage extends Model
{
    protected $fillable = [
      'image',
      'thumbnail'
    ];
    public function articles (  ) {
        return $this->belongsTo('App\models\analyst\monthly\Monthlyarticle');
    }
}
