<?php

namespace App\models\analyst\various;

use App\models\imodel;
use App\SearchableTrait;

class Variousarticle extends imodel
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
          'variousarticles.title' => 10,
          'variousarticles.body' => 10,

        ],
    ];

    public function subcategory (  ) {
        return $this->belongsTo('App\models\analyst\various\Varioussubcategory','varioussubcategory_id','id','varioussubcategories');
    }
    public function category (  ) {
        return $this->belongsTo('App\models\analyst\various\Variouscategory','variouscategory_id','id','variouscategories');
    }
    public function users (  ) {
        return $this->belongsTo('App\User');
    }
	public function report (  ) {
		return $this->belongsTo('App\models\analyst\various\Variousreport', 'variousreport_id','id','variousreport');
	}
    public function images (  ) {
        return $this->hasMany('App\models\analyst\various\Variousimage');
    }
    public function scopeActive($query)
    {
        return $query->where('published', 2);
    }

}
