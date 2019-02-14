<?php

namespace App\models\analyst\yearly;

use Illuminate\Database\Eloquent\Model;

class Countrycatalog extends Model
{
    protected $fillable =['published'];

    public function regions (  ) {
        return $this->hasMany('App\models\analyst\yearly\Region');
    }
    public function scopeActive($query)
    {
        return $query->where('published', 2);
    }
}
