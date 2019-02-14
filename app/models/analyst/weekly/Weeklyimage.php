<?php

namespace App\models\analyst\weekly;

use Illuminate\Database\Eloquent\Model;

class Weeklyimage extends Model
{
    protected $fillable = [
      'image',
      'thumbnail'
    ];
    public function articles (  ) {
        return $this->belongsTo('App\models\analyst\weekly\Weeklyarticle');
    }
}
