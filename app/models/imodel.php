<?php
/**
 * Created by PhpStorm.
 * User: sergeyskorohod
 * Date: 7/15/18
 * Time: 4:44 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class imodel extends Model
{
    public function companies (  ) {
        return $this->belongsToMany('App\Models\Company');
    }
    public function countries (  ) {
        return $this->belongsToMany('App\Models\Country');
    }
    public function personalities (  ) {
        return $this->belongsToMany('App\Models\Personality');
    }
    public function vvttypes (  ) {
        return $this->belongsToMany('App\Models\VvtType');
    }
    public function users (  ) {
        return $this->belongsTo('App\User');
    }
    public function scopeActive($query)
    {
        return $query->where('published', 2);
    }
    public function scopeWithout_tags ( $query ) {
        return $query->doesnthave('countries')
                     ->doesnthave('companies')
                     ->doesnthave('personalities')
                     ->doesnthave('vvttypes');

    }
}