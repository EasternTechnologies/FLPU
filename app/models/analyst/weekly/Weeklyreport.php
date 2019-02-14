<?php

namespace App\models\analyst\weekly;

use Illuminate\Database\Eloquent\Model;

class Weeklyreport extends Model
{
    protected $fillable = ['published'];

    public function articles (  ) {
        return $this->hasMany('App\models\analyst\weekly\Weeklyarticle');
    }

    public function scopeActive($query)
    {
        return $query->where('published', 2);
    }
}
