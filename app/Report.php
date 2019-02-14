<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
	protected $fillable = [
		'title',
		'date_start',
		'date_end',
		'status',
		'number',
		'description',
	];

	public function types () {
		return $this->belongsTo('App\ReportType', 'type_id');
	}

	public function articles () {
		return $this->hasMany('App\ArticleReports');
	}


	public function categories()
	{
		return $this->hasMany('App\Category', 'report_id');
	}


	public function scopeActive($query)
	{
		return $query->where('status', 2);
	}
}
