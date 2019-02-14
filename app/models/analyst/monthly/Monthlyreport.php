<?php

namespace App\models\analyst\monthly;

use Illuminate\Database\Eloquent\Model;

class Monthlyreport extends Model
{
    protected $fillable = ['published'];
    public function articles (  ) {
        return $this->hasMany('App\models\analyst\monthly\Monthlyarticle');
    }
    public function scopeActive($query)
    {
        return $query->where('published', 2);
    }
}
