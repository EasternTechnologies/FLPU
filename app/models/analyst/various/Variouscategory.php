<?php

namespace App\models\analyst\various;

use Illuminate\Database\Eloquent\Model;

class Variouscategory extends Model
{
    protected $fillable =['title'];
    public function report (  ) {
        return $this->belongsTo('App\models\analyst\various\Variousreport','variousreport_id');
    }
    public function articles (  ) {
        return $this->hasMany('App\models\analyst\various\Variousarticle');
    }
    public function subcategories (  ) {
        return $this->hasMany('App\models\analyst\various\Varioussubcategory');
    }
}
