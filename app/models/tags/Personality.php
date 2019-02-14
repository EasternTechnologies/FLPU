<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personality extends Model
{

    protected $fillable = ['title'];

    public function articles () {
        return $this->belongsToMany('App\ArticleReports');
    }

    public function countries () {
        return $this->belongsToMany('App\Models\Country');
    }

    public function vvttypes () {
        return $this->belongsToMany('App\Models\VvtType');
    }
    
    public function addCountries($ids)
    {
        $this->countries()->detach();
        $this->countries()->syncWithoutDetaching($ids);
    }
}
