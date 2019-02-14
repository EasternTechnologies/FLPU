<?php

namespace App\models\analyst\yearly;

use Illuminate\Database\Eloquent\Model;

class Yearlycategory extends Model
{
    protected $fillable =['title'];
    public function report (  ) {
        return $this->belongsTo('App\models\analyst\yearly\Yearlyreport','yearlyreport_id', 'id','yearlyreports');
    }
    public function articles (  ) {
        return $this->hasMany('App\models\analyst\yearly\Yearlyarticle');
    }
    public function subcategories (  ) {
        return $this->hasMany('App\models\analyst\yearly\Yearlysubcategory');
    }
}
