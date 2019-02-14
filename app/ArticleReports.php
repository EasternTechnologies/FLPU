<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Elasticquent\ElasticquentTrait;

class ArticleReports extends Model
{
	protected $fillable = [
		'title',
		'place',
		'date_start',
		'date_end',
		'place',
		'status',
		'description',
	];
	//use SearchableTrait;
	use ElasticquentTrait;

	/**
	 * Searchable rules.
	 *
	 * @var array
	 */
	protected $searchable = [
		/**
		 * Columns and their priority in search results.
		 * Columns with higher values are more important.
		 * Columns with equal values have equal importance.
		 *
		 * @var array
		 */
		'columns' => [
			'article_reports.title' => 10,
			'article_reports.place' => 10,
			'article_reports.description' => 10,

		],
	];

	protected $mappingProperties = [

		'title' => [
			'type' => 'text',
			'analyzer' => 'standard'
		],
		'place' => [
			'type' => 'text',
			'analyzer' => 'standard'
		],
		'description' => [
			'type' => 'text',
			'analyzer' => 'russian'
		]

	];

	public function reports (){
		return $this->belongsTo('App\Report','report_id');
	}

    public function category () {
        return $this->belongsTo('App\Category');
    }
    public function subcategory () {
        return $this->belongsTo('App\Subcategory');
    }

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
		return $query->where('status', 2);
	}
	public function scopeWithout_tags ( $query ) {
		return $query->doesnthave('countries')
		             ->doesnthave('companies')
		             ->doesnthave('personalities')
		             ->doesnthave('vvttypes');

	}

    public function images () {

	    return $this->hasMany('App\Image');

    }
}
