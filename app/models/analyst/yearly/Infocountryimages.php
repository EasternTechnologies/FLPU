<?php

namespace App\models\analyst\yearly;

use Illuminate\Database\Eloquent\Model;

class Infocountryimages extends Model
{
    protected $fillable = [
      'image',
      'thumbnail'
    ];
    public function infocountry (  ) {
        return $this->belongsTo('App\Models\InfoCountry');
    }
}
