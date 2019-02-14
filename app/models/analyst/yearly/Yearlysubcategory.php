<?php

namespace App\models\analyst\yearly;

use Illuminate\Database\Eloquent\Model;

class Yearlysubcategory extends Model
{
    protected $fillable =['title'];

    public function category (  ) {
        return $this->belongsTo('App\models\analyst\yearly\Yearlycategory', 'yearlycategory_id','id','yearlycategories');
    }

    public function articles (  ) {
        return $this->hasMany('App\models\analyst\yearly\Yearlyarticle');
    }
}
