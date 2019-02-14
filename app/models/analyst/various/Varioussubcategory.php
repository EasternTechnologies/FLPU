<?php

namespace App\models\analyst\various;

use Illuminate\Database\Eloquent\Model;

class Varioussubcategory extends Model
{
    protected $fillable =['title'];

    public function category (  ) {
        return $this->belongsTo('App\models\analyst\various\Variouscategory','variouscategory_id','id','variouscategories');
    }

    public function articles (  ) {
        return $this->hasMany('App\models\analyst\various\Variousarticle');
    }
}
