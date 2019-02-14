<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['title'];

	public function articles () {
		return $this->belongsToMany('App\ArticleReports');
	}

	public function companies (  ) {
		return $this->belongsToMany('App\Models\Company');
	}

	public function personalities (  ) {
		return $this->belongsToMany('App\Models\Personality');
	}
}
