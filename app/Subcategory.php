<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
	protected $fillable = [
		'title',
		'description',
	];


	public function article_reports (  ) {
		return $this->hasMany('App\ArticleReports');
	}

    public function category (  ) {
        return $this->belongsTo('App\Category');
    }
}
