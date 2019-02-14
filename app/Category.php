<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	protected $fillable = [
		'title',
		'description',
	];
    public function report_type (  ) {
        return $this->belongsTo('App\ReportType');
    }

	public function report (  ) {
		return $this->belongsTo('App\Report');
	}

    public function subcategories (  ) {
        return $this->hasMany('App\Subcategory');
    }

	public function article_reports (  ) {
		return $this->hasMany('App\ArticleReports');
	}
}
