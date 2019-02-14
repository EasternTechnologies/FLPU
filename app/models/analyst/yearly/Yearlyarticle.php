<?php

namespace App\models\analyst\yearly;

use App\models\imodel;
use App\SearchableTrait;

class Yearlyarticle extends imodel
{

    protected $fillable = [
      'body',
      'title',
      'year',
      'month',
      'week',
      'start_period',
      'end_period',
      'published',
    ];


    use SearchableTrait;

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
          'yearlyarticles.title' => 10,
          'yearlyarticles.body' => 10,

        ],
    ];

    public function subcategory (  ) {
        return $this->belongsTo('App\models\analyst\yearly\Yearlysubcategory','yearlysubcategory_id','id','yearlysubcategories');
    }
    public function category (  ) {
        return $this->belongsTo('App\models\analyst\yearly\Yearlycategory', 'yearlycategory_id', 'id', 'yearlycategories');
    }
	public function report (  ) {
		return $this->belongsTo('App\models\analyst\yearly\Yearlyreport', 'yearlyreport_id', 'id', 'yearlyreports');
	}
    public function users (  ) {
        return $this->belongsTo('App\User');
    }
    public function images (  ) {
        return $this->hasMany('App\models\analyst\yearly\Yearlyimage');
    }
    public function scopeActive($query)
    {
        return $query->where('published', 2);
    }

}
