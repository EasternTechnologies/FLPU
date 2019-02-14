<?php

namespace App\models\analyst\exhibitions;

use Illuminate\Database\Eloquent\Model;

class Plannedexhibitionimage extends Model
{
    protected $fillable = [
      'image',
      'thumbnail'
    ];
    public function article (  ) {
        return $this->belongsTo('App\models\analyst\exhibitions\Plannedexhibition');
    }
}
