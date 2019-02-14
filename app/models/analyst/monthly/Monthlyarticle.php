<?php

namespace App\models\analyst\monthly;

use App\SearchableTrait;
use App\models\imodel;

class Monthlyarticle extends imodel
{
    protected $fillable = [
      'body',
      'title',
      'year',
      'month',
      'week',
      'start_period',
      'end_period',
      'published'
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
          'monthlyarticles.title' => 10,
          'monthlyarticles.body' => 10,

        ],
    ];


    public function monthlyreport () {
        return $this->belongsTo('App\models\analyst\monthly\Monthlyreport');
    }
    public function category (  ) {
        return $this->belongsTo('App\Category');
    }
    public function subcategory (  ) {
        return $this->belongsTo('App\Subcategory');
    }
    public function users (  ) {
        return $this->belongsTo('App\User');
    }
    public function monthlyimages (  ) {
        return $this->hasMany('App\models\analyst\monthly\Monthlyimage');
    }
    public function scopeActive($query)
    {
        return $query->where('published', 2);
    }

}
