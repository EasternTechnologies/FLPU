<?php

namespace App\models\analyst\yearly;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = ['title', 'overview', 'start_period', 'end_period', 'published'];
    public function countrycatalog (  ) {
        return $this->belongsTo('App\models\analyst\yearly\Countrycatalog');
    }

    public function countries (  ) {
        return $this->hasMany('App\models\analyst\yearly\InfoCountry');
    }
}
