<?php

namespace App\models\analyst\yearly;

use Illuminate\Database\Eloquent\Model;

class Yearlyimage extends Model
{
    protected $fillable = ['image','thumbnail' ];
    public function article (  ) {
        return $this->belongsTo('App\models\analyst\yearly\Yearlyarticle');
    }
}
